.PHONY: default up stop restart down install lint drush composer sh yarn

# Make sure the local file with docker-compose overrides exist.
$(shell cp -n \.\/\.docker\/docker-compose\.override\.default\.yml \.\/\.docker\/docker-compose\.override\.yml)

# Create a .env file if not exists with default env variables.
$(shell cp -n \.env\.default \.env)

# Create a reactjs/.env file if not exists with default env variables.
$(shell cp -n \.env\.default \.\/reactjs\/\.env)

# Make all variables from the environment file available here.
include .env

# Define two users for with different permissions within the container.
# docker-drupal is applicable only for php containers.
docker-drupal = docker-compose exec -T --user=82:82 php ${1}
docker = docker-compose exec -T php ${1}

# Defines colors for echo, eg: @echo "${GREEN}Hello World${COLOR_END}". More colors: https://stackoverflow.com/a/43670199/3090657
YELLOW=\033[0;33m
RED=\033[0;31m
GREEN=\033[0;32m
COLOR_END=\033[0;37m

default: up

up:
	@echo "${YELLOW}Build and run containers...${COLOR_END}"
	docker-compose up -d  --remove-orphans
	@echo "${GREEN}Done!${COLOR_END}"
	@echo "${YELLOW}Streaming the React.js application logs...${COLOR_END}"
	docker-compose logs -f node

stop:
	@echo "${YELLOW}Stopping containers...${COLOR_END}"
	docker-compose stop

restart:
	@echo "${YELLOW}Restarting containers...${COLOR_END}"
	$(MAKE) -s down
	$(MAKE) -s up

down:
	@echo "${YELLOW}Removing network & containers...${COLOR_END}"
	docker-compose down -v --remove-orphans

install:
	@echo "${YELLOW}Installing React.js application...${COLOR_END}"
	docker-compose run node yarn install
	docker-compose down  --remove-orphans
	docker-compose up -d  --remove-orphans
	@echo "${YELLOW}Installing Drupal (Contenta CMS)...${COLOR_END}"
	docker-compose exec php composer install
	docker-compose exec php drush si --root=web --account-pass=admin contenta_jsonapi -y
	@echo "${YELLOW}Removing demo Contenta content...${COLOR_END}"
	docker-compose exec php drush pmu recipes_magazin -y
	@echo "${YELLOW}Installing configs...${COLOR_END}"
	docker-compose exec php drush cim -y
	@echo "${GREEN}The platform is ready to use!${COLOR_END}"

drush:
	@echo "${YELLOW}Running Drush command...${COLOR_END}"
	$(eval ARGS := $(filter-out $@,$(MAKECMDGOALS)))
	$(call docker-drupal, drush $(ARGS) --root=web -y)

composer:
	@echo "${YELLOW}Running Composer command...${COLOR_END}"
	$(eval ARGS := $(filter-out $@,$(MAKECMDGOALS)))
	$(call docker-drupal, composer $(ARGS) -vvv)

sh:
	@echo "${YELLOW}Opening shell inside of php container...${COLOR_END}"
	$(eval ARGS := $(filter-out $@,$(MAKECMDGOALS)))
	docker-compose exec php sh $(ARGS)

yarn:
	@echo "${YELLOW}Running yarn command...${COLOR_END}"
	$(eval ARGS := $(filter-out $@,$(MAKECMDGOALS)))
	docker-compose run node yarn $(ARGS)

lint:
	@echo "${YELLOW}Checking coding styles...${COLOR_END}"
	docker-compose run node yarn eslint --fix

# https://stackoverflow.com/a/6273809/1826109
%:
	@:
