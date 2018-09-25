# Command list

- `make install` - installs the whole application locally.
- `make update` - runs all necessary commands to update your local environment after external changes (switch to another git branch, etc).
- `make install:platformsh` - if using platform.sh, will install the project from Platform.sh master database and sync files from it to the local environment.
- `make up` - runs the application in console debug mode.
- `make stop` - pauses the application.
- `make down` - completely stops the application and removes docker containers.
- `make restart` - restarts the application containers.
- `make drush` - runs drush inside of php container. Example of use: `make drush cr`.
- `make composer` - runs composer inside of php container. Example of use: `make composer require drupal/fieldable_path`.
- `make yarn` - runs yarn inside of node container. Example of use: `make yarn add lodash`.
- `make code:check` - checks Drupal / React.js coding standards compliance.
- `make code:fix` - checks Drupal / React.js coding standards compliance and fixes issues if possible.
- `make tests:run` - runs all types of tests (unit, API, acceptance). If you want to run only 1 type of tests, run `make tests:run acceptance` for example.
