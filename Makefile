.PHONY: default pull up stop down restart \
exec exec\:wodby exec\:root drush composer \
prepare\:backend prepare\:frontend prepare\:platformsh \
install platform\:install update \
db\:dump db\:drop db\:import \
platform\:files\:sync platform\:files\:sync\:public platform\:files\:sync\:private \
code\:check code\:fix \
npm logs \
tests\:prepare tests\:run tests\:cli tests\:autocomplete

# Create local environment files.
$(shell cp -n \.\/\.docker\/docker-compose\.override\.default\.yml \.\/\.docker\/docker-compose\.override\.yml)
# If .env file doesn't exist yet - copy it from the default one.
# Then if OS is Linux we change the PHP_TAG:
#  - uncomment all the strings containing 'PHP_TAG'
#  - comment all the strings containing 'PHP_TAG' and '-dev-macos-'
$(shell ! test -e \.env && cp \.env\.default \.env && uname -s | grep -q 'Linux' && sed -i '/PHP_TAG/s/^# //g' \.env && sed -i -E '/PHP_TAG.+-dev-macos-/s/^/# /g' \.env)

include .env

# Define function to highlight messages.
# @see https://gist.github.com/leesei/136b522eb9bb96ba45bd
# "@echo -p [...]" allow print with color on archlinux
yellow = \033[38;5;3m
bold = \033[1m
reset = \033[0m
message = @echo -p "${yellow}${bold}${1}${reset}"

# Define 3 users with different permissions within the container.
# docker-www-data is applicable only for php container.
docker-www-data = docker-compose exec --user=82:82 $(firstword ${1}) sh -c "$(filter-out $(firstword ${1}), ${1})"
docker-wodby = docker-compose exec $(firstword ${1}) sh -c "$(filter-out $(firstword ${1}), ${1})"
docker-root = docker-compose exec --user=0:0 $(firstword ${1}) sh -c "$(filter-out $(firstword ${1}), ${1})"

default: up

pull:
	$(call message,$(PROJECT_NAME): Downloading / updating Docker images...)
	docker-compose pull
	docker pull $(DOCKER_PHPCS)
	docker pull $(DOCKER_ESLINT)

up:
	$(call message,$(PROJECT_NAME): Starting Docker containers...)
	docker-compose up -d --remove-orphans --scale codecept=0

stop:
	$(call message,$(PROJECT_NAME): Stopping Docker containers...)
	docker-compose stop

down:
	$(call message,$(PROJECT_NAME): Removing Docker network & containers...)
	docker-compose down --remove-orphans

restart:
	$(call message,$(PROJECT_NAME): Restarting Docker containers...)
	@$(MAKE) -s down
	@$(MAKE) -s up

exec:
    # Remove the first argument from the list of make commands.
	$(eval ARGS := $(filter-out $@,$(MAKECMDGOALS)))
	$(eval TARGET := $(firstword $(ARGS)))
	docker-compose exec --user=82:82 $(TARGET) sh

exec\:wodby:
    # Remove the first argument from the list of make commands.
	$(eval ARGS := $(filter-out $@,$(MAKECMDGOALS)))
	$(eval TARGET := $(firstword $(ARGS)))
	docker-compose exec $(TARGET) sh

exec\:root:
    # Remove the first argument from the list of make commands.
	$(eval ARGS := $(filter-out $@,$(MAKECMDGOALS)))
	$(eval TARGET := $(firstword $(ARGS)))
	docker-compose exec --user=0:0 $(TARGET) sh

drush:
    # Remove the first argument from the list of make commands.
	$(eval COMMAND_ARGS := $(filter-out $@,$(MAKECMDGOALS)))
	$(call docker-www-data, php drush $(COMMAND_ARGS) --yes)

composer:
    # Remove the first argument from the list of make commands.
	$(eval COMMAND_ARGS := $(filter-out $@,$(MAKECMDGOALS)))
	$(call docker-wodby, php composer $(COMMAND_ARGS))

########################
# Project preparations #
########################

prepare\:backend:
	$(call message,$(PROJECT_NAME): Installing/updating Drupal (Contenta CMS) dependencies...)
	-$(call docker-wodby, php composer install)
	$(call message,$(PROJECT_NAME): Updating permissions for public files...)
	$(call docker-root, php mkdir -p web/sites/default/files)
	$(call docker-root, php chown -R www-data: web/sites/default/files)
	$(call docker-root, php chmod 666 web/sites/default/settings.php)

prepare\:frontend:
	$(call message,$(PROJECT_NAME): Installing dependencies for React.js application...)
	docker-compose run --rm node npm install

prepare\:platformsh:
	$(call message,$(PROJECT_NAME): Setting Platform.sh git remote..)
	platform project:set-remote $(PLATFORM_PROJECT_ID)

###################################
# Installation from the bottom up #
###################################

install:
	@$(MAKE) -s prepare\:frontend
	@$(MAKE) -s up
	@$(MAKE) -s prepare\:backend
	$(call message,$(PROJECT_NAME): Installing Contenta CMS...)
	$(call docker-www-data, php drush site-install contenta_jsonapi --site-name=$(PROJECT_NAME) --account-pass=admin --yes)
	$(call message,$(PROJECT_NAME): Preparing test suite...)
	@$(MAKE) -s tests\:prepare
	@$(MAKE) -s tests\:autocomplete
	$(call message,$(PROJECT_NAME): The application is ready!)

######################################################
# Installation from existing Platform.sh environment #
######################################################

platform\:install:
	@$(MAKE) -s prepare\:platformsh
	@$(MAKE) -s prepare\:frontend
	@$(MAKE) -s up
	@$(MAKE) -s prepare\:backend
	@$(MAKE) -s platform\:files\:sync
	@$(MAKE) -s platform\:db\:dump
	@$(MAKE) -s db\:import
	@$(MAKE) -s update
	$(call message,$(PROJECT_NAME): The application is ready!)

#########################################
# Update project after external changes #
#########################################

update:
	@$(MAKE) -s prepare\:frontend
	$(call message,$(PROJECT_NAME): Installing/updating backend dependencies...)
	-$(call docker-wodby, php composer install)
	$(call message,$(PROJECT_NAME): Rebuilding Drupal cache...)
	@$(MAKE) -s drush cache-rebuild
	$(call message,$(PROJECT_NAME): Applying database updates...)
	@$(MAKE) -s drush updatedb
	$(call message,$(PROJECT_NAME): Importing configurations...)
	@$(MAKE) -s drush config-import
	$(call message,$(PROJECT_NAME): Applying entity schema updates...)
	@$(MAKE) -s drush entup

#######################
# Database operations #
#######################

platform\:db\:dump:
	$(call message,$(PROJECT_NAME): Creating DB dump from Platform.sh...)
	mkdir -p $(BACKUP_DIR)
	-platform db:dump -y --project=$(PLATFORM_PROJECT_ID) --environment=$(PLATFORM_ENVIRONMENT) --app=$(PLATFORM_APPLICATION_BACKEND) --relationship=$(PLATFORM_RELATIONSHIP_BACKEND) --gzip --file=$(BACKUP_DIR)/$(DB_DUMP_NAME).sql.gz

db\:drop:
	$(call message,$(PROJECT_NAME): Dropping DB from the local environment...)
	@$(MAKE) -s drush sql-drop

db\:import:
	@$(MAKE) -s db\:drop
	$(call message,$(PROJECT_NAME): Importing DB to the local environment...)
	$(call docker-www-data, php zcat ${BACKUP_DIR}/${DB_DUMP_NAME}.sql.gz | drush --root=web sql-cli)

####################
# Files operations #
####################

platform\:files\:sync:
	@$(MAKE) -s platform\:files\:sync\:public
	@$(MAKE) -s platform\:files\:sync\:private

platform\:files\:sync\:public:
	$(call message,$(PROJECT_NAME): Creating public files directory...)
	$(call docker-wodby, php mkdir -p web/sites/default/files)

	$(call message,$(PROJECT_NAME): Changing public files ownership to wodby...)
	$(call docker-root, php chown -R wodby: web/sites/default/files)

	$(call message,$(PROJECT_NAME): Downloading public files from Platform.sh...)
	-platform mount:download -y --project=$(PLATFORM_PROJECT_ID) --environment=$(PLATFORM_ENVIRONMENT) --app=$(PLATFORM_APPLICATION_BACKEND) \
        --mount=web/sites/default/files --target=drupal/web/sites/default/files \
        --exclude=css/* --exclude=js/* --exclude=php/* --exclude=styles/*

	$(call message,$(PROJECT_NAME): Changing public files ownership to www-data...)
	$(call docker-root, php chown -R www-data: web/sites/default/files)

platform\:files\:sync\:private:
	$(call message,$(PROJECT_NAME): Creating private files directory...)
	$(call docker-wodby, php mkdir -p web/private)

	$(call message,$(PROJECT_NAME): Changing private files ownership to wodby...)
	$(call docker-root, php chown -R wodby: web/private)

	$(call message,$(PROJECT_NAME): Downloading private files from Platform.sh...)
	-platform mount:download -y --project=$(PLATFORM_PROJECT_ID) --environment=$(PLATFORM_ENVIRONMENT) --app=$(PLATFORM_APPLICATION_BACKEND) \
        --mount=private --target=drupal/web/private

	$(call message,$(PROJECT_NAME): Changing private files ownership to www-data...)
	$(call docker-root, php chown -R www-data: web/private)

#######################
# Code quality checks #
#######################

code\:check:
	$(call message,$(PROJECT_NAME): Checking PHP code for compliance with coding standards...)
	docker run --rm \
      -v $(shell pwd)/drupal/web/modules/custom:/app/modules \
      $(DOCKER_PHPCS) phpcs \
      -s --colors --standard=Drupal,DrupalPractice .

	$(call message,$(PROJECT_NAME): Checking Drupal Javascript code for compliance with coding standards...)
	docker run --rm \
      -v $(shell pwd)/drupal/web/modules/custom:/eslint/modules \
      -v $(shell pwd)/drupal/.eslintrc.json:/eslint/.eslintrc.json \
      $(DOCKER_ESLINT) .

	$(call message,$(PROJECT_NAME): Checking React.js code for compliance with coding standards...)
	docker-compose run -T --rm node npm --silent run eslint

code\:fix:
	$(call message,$(PROJECT_NAME): Auto-fixing Drupal PHP code issues...)
	docker run --rm \
      -v $(shell pwd)/drupal/web/modules/custom:/app/modules \
      $(DOCKER_PHPCS) phpcbf \
      -s --colors --standard=Drupal,DrupalPractice .

	$(call message,$(PROJECT_NAME): Auto-fixing Drupal JS code issues...)
	docker run --rm \
      -v $(shell pwd)/drupal/web/modules/custom:/eslint/modules \
      -v $(shell pwd)/drupal/.eslintrc.json:/eslint/.eslintrc.json \
      $(DOCKER_ESLINT) --fix .

	$(call message,$(PROJECT_NAME): Auto-fixing React.js code issues...)
	docker-compose run -T --rm node npm --silent run eslint --fix

#######################
# Frontend operations #
#######################

npm:
	$(call message,$(PROJECT_NAME): Running npm command...)
	$(eval ARGS := $(filter-out $@,$(MAKECMDGOALS)))
	docker-compose run --rm node npm $(ARGS)

logs:
	$(call message,$(PROJECT_NAME): Streaming the Next.js application logs...)
	docker-compose logs -f node

##############################
# Testing framework commands #
##############################

tests\:prepare:
	$(call message,$(PROJECT_NAME): Preparing Codeception framework for testing...)
	docker-compose run --rm codecept build

tests\:run:
	$(call message,$(PROJECT_NAME): Running Codeception tests...)
	$(eval ARGS := $(filter-out $@,$(MAKECMDGOALS)))
	docker-compose run --rm codecept run $(ARGS) --debug

tests\:cli:
	$(call message,$(PROJECT_NAME): Opening Codeception container CLI...)
	docker-compose run --rm --entrypoint bash codecept

tests\:autocomplete:
	$(call message,$(PROJECT_NAME): Copying Codeception codbasee in .codecept folder to enable IDE autocomplete...)
	docker-compose up -d codecept
	rm -rf .codecept
	docker cp $(PROJECT_NAME)_codecept:/repo/ .codecept
	rm -rf .codecept/.git

# https://stackoverflow.com/a/6273809/1826109
%:
	@:
