# Installation

1. Clone git repo locally, then

    ```
    cd drupal_reactjs_boilerplate
    make install
    ```

2. That's it, the app is ready!
 
# Access applications
 
| URL                                | Name                |
| -----------------------------------| ------------------- |
| http://app.docker.localhost        | ReactJS application |
| http://app.docker.localhost/admin/ | Drupal 8            |
| http://pma.docker.localhost        | PhpMyAdmin          |
| http://mailhog.docker.localhost    | Mailhog             |
 
# What is included into this boilerplate?
 
### General
 
- One command installation. It's awesome, isn't it? :)
- Docker based local development powered by [docker4drupal](https://github.com/wodby/docker4drupal) containers. Unification, yay!
- [Makefiles](https://www.gnu.org/software/make/manual/html_node/Introduction.html) powered short commands for routine operations at your service. Works out of the box for Mac & Linux. Sorry Windows users - it will not work for you (but if you're developing on Windows you should get used to this kind of pain already).
- The whole project is ready to be deployed to [Platform.sh](https://platform.sh/) hosting
- Continues Integration which automatically deploys application to Platform.sh & runs code style checks for backend & frontend. Continues integration is based on [CircleCI](https://circleci.com/).
- Support of http auth protection for both frontend and backend applications. Useful for dev copies of application. Note: `HTTP_AUTH_USER` and `HTTP_AUTH_PASS` environment variables have to be set to make feature work.
- 

### Frontend

- Example of backend request with data fetching & rendering
- Server Side rendering support (the application is based on [Next.js](https://github.com/zeit/next.js/))
- Live reloading for any `css` / `js` changes
- Opinionated but (hopefully) quite beautiful code style pattern based on Airbnb's work. Just run `make lint` to get the frontend code checked against coding standards.
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
 
# Mac OS Docker configs

By default Docker is configured to run on all environments. Mac has issues with files sync performance by default. So to get it fixed, open `./docker/docker-compose.override.yml` file and follow the instructions to improve file sync performance. When it's done, just run `make restart`.

# Command list

- `make install` - installs the whole application locally.
- `make up` - runs the application in console debug mode.
- `make stop` - pauses the application.
- `make down` - completely stops the application and removes docker containers.
- `make restart` - restarts the application containers.
- `make drush` - runs drush inside of php container. Example of use: `make drush cr`.
- `make yarn` - runs yarn inside of node container. Example of use: `make yarn add lodash`.
- `make lint` - checks coding standards and fixes issues if possible.
