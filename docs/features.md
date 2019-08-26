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
- Opinionated code style pattern based on Airbnb's work. Just run `make code:check` to get the frontend code checked against coding standards.
- Configured `Redux` + `Redux saga` with example
- Configured `robots.txt` file
- Beautiful page transition indicator

### Backend

- Always fresh version of [Contenta CMS](http://www.contentacms.org) (Drupal powered distribution focused on abstracting of content).
- Configured Drupal installation to work with React.js application
- Nice admin theme
- Other cool features of Contenta CMS out of the box. You should really [check them out](http://contentacms.readthedocs.io/en/latest/)! 
