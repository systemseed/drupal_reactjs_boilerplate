[![CircleCI](https://circleci.com/gh/systemseed/drupal_reactjs_boilerplate.svg?style=svg)](https://circleci.com/gh/systemseed/drupal_reactjs_boilerplate)

# Installation

1. Project setup is Mac by default, so if you are running MacOS then

    ```
    cd drupal_reactjs_boilerplate
    make install
    ```

2. If you run Linux then
    - run `make prepare` command to create local environment files
    - edit the created `.env` file in the root of the project and uncomment `PHP_TAG` version for Linux
    - run `make install`

3. That's it, the app is ready! Login credentials for Drupal is `admin` / `admin`.

# Access applications
 
| URL                                     | Name                |
| ----------------------------------------| ------------------- |
| http://app.docker.localhost             | ReactJS application |
| http://drupal.docker.localhost          | Drupal 8            |

# Playing with demo content

This boilerplate comes development ready by default, therefore demo content is disabled. If you want to explore demo content from Contenta CMS, you need to enable `Recipes Magazin` module:

- `make drush en recipes_magazin`
- `make drush cr` (that's not needed usually, but JSON API endpoints becomes not available after module's install. Cache rebuild fixes the problem.)

Note that the frontend users fetching request will become broken in this case, because the enabled module slightly changes the API structure.
To fix it, open `./reactjs/api/user.js` file and replace `.get('/api/user/user')` with `.get('/api/users')`.

# What is included into this boilerplate?
 
### General
 
- One command installation. It's awesome, isn't it? :)
- Docker based local development powered by [docker4drupal](https://github.com/wodby/docker4drupal) containers. Unification, yay!
- [Makefiles](https://www.gnu.org/software/make/manual/html_node/Introduction.html) powered short commands for routine operations at your service. Works out of the box for Mac & Linux. Sorry Windows users - it will not work for you (but if you're developing on Windows you should get used to this kind of pain already).
- The whole project is ready to be deployed to [Platform.sh](https://platform.sh/) hosting
- Continuous Integration which automatically deploys the application to Platform.sh & runs code style checks for backend & frontend. Continuous integration is running on [CircleCI](https://circleci.com/).
- Support of http auth protection for both frontend and backend applications. Useful for dev copies of the application. Note: `HTTP_AUTH_USER` and `HTTP_AUTH_PASS` environment variables have to be set to make feature work.

### Frontend

- Example of backend request with data fetching & rendering
- Server Side rendering support (the application is based on [Next.js](https://github.com/zeit/next.js/))
- Support of `.scss` files per component. The application will automatically add compiled `.css` file for each page with styles from components used for the page.
- Live reloading for any `css` / `js` changes
- Mobile ready navigation menu
- Opinionated code style pattern based on Airbnb's work. Just run `make code:check` to get the frontend code checked against coding standards.
- Configured `Redux` + `Redux saga` with example
- Configured `robots.txt` file
- Included `Bootstrap 4` support (can be easily removed if necessary)
- Beautiful page transition indicator

### Backend

- Always fresh version of [Contenta CMS](http://www.contentacms.org) (Drupal powered distribution focused on abstracting of content).
- Configured Drupal installation to work with React.js application
- Nice admin theme
- Other cool features of Contenta CMS out of the box. You should really [check them out](http://contentacms.readthedocs.io/en/latest/)! 

### Automated testing

The boilerplate comes with 3 different types of tests:

- `Unit / Integration tests` for Drupal. This type of tests great for testing of any Drupal specific code. See example in `./tests/unit/example/ExampleTest.php`.
- `API tests`. This type of tests is used to cover customizations of Drupal API endpoints. For example, custom REST endpoints or modifications of JSON API responses. See example in `./tests/api/example/ExampleCest.php`.
- `Acceptance tests`. This type of tests emulates real user behavior in a real browser and suited to automate end-to-end testing of project features. See example in `./tests/acceptance/example/ExampleCest.php`.

The coolest part of this set up is that all these tests **have Drupal API support & database connection**. At any point you may query data from Drupal and compare it with the expected behavior.

Here's list of shorthands to run tests locally (make sure all docker containers are up by running `make up` before):

- `make tests:run` - runs all types of tests
- `make tests:run unit` - runs Unit / Integration tests only
- `make tests:run api` - runs API tests only
- `make tests:run acceptance` - runs Acceptance tests only
- `make tests:run tests/api/example/ExampleCest` - runs all tests from a specified file
- `make tests:run tests/api/example/ExampleCest::authenticateAdmin` - runs only specified test

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
