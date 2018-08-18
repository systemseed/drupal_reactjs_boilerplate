# Installation

1. Project setup is Mac by default, so if you are running MacOS then

    ```
    cd drupal_reactjs_boilerplate
    make install
    ```

2. If you happen to run Linux then
    - run `make stop` command to create local environment files
    - edit the created .env file to change the PHP_TAG value to the Linux one
    - run `make install`

3. That's it, the app is ready!

# Access applications
 
| URL                                     | Name                |
| ----------------------------------------| ------------------- |
| http://app.docker.localhost             | ReactJS application |
| http://drupal.docker.localhost          | Drupal 8            |
| http://pma.drupal.docker.localhost      | PhpMyAdmin          |
| http://mailhog.drupal.docker.localhost  | Mailhog             |
 
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
- Live reloading for any `css` / `js` changes
- Opinionated but (hopefully) beautiful code style pattern based on Airbnb's work. Just run `make lint` to get the frontend code checked against coding standards.
- `SCSS` support for quick theming start. The `.scss` files expected to be added within component folders for better long term support.
- Configured `Redux` + `Redux saga` with example
- Configured `robots.txt` file
- Included `Bootstrap 4` support
- Beautiful page transition indicator

### Backend
  
- Always fresh version of [Contenta CMS](http://www.contentacms.org) (Drupal powered distribution focused on abstracting of content).
- Configured Drupal installation to work with React.js application
- Nice admin theme
- Other features of Contenta CMS out of the box. You should really [check them out](http://contentacms.readthedocs.io/en/latest/)! 

# Command list

- `make install` - installs the whole application locally.
- `make up` - runs the application in console debug mode.
- `make stop` - pauses the application.
- `make down` - completely stops the application and removes docker containers.
- `make restart` - restarts the application containers.
- `make drush` - runs drush inside of php container. Example of use: `make drush cr`.
- `make composer` - runs composer inside of php container. Example of use: `make composer require drupal/fieldable_path`.
- `make sh` - opens shell inside of php container. If the command has options, then the command after `make sh` will be executed inside of php container.
- `make yarn` - runs yarn inside of node container. Example of use: `make yarn add lodash`.
- `make lint` - checks coding standards and fixes issues if possible.
