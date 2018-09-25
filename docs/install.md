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

# Access the application
 
| URL                                     | What's there        |
| ----------------------------------------| ------------------- |
| http://app.docker.localhost             | ReactJS app         |
| http://drupal.docker.localhost          | Drupal 8            |

# Playing with demo content

This boilerplate comes development ready by default, therefore demo content is disabled. If you want to explore demo content from Contenta CMS, you need to enable `Recipes Magazin` module:

- `make drush en recipes_magazin`
- `make drush cr` (that's not needed usually, but JSON API endpoints becomes not available after module's install. Cache rebuild fixes the problem.)

Note that the frontend users fetching request will become broken in this case, because the enabled module slightly changes the API structure.
To fix it, open `./reactjs/api/user.js` file and replace `.get('/api/user/user')` with `.get('/api/users')`.
