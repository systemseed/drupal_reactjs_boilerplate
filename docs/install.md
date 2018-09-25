# Installation

1. The boilerplate is set to serve Mac users by default. So if you use **macOS** then after git clone, simply call

    ```
    cd drupal_reactjs_boilerplate
    make install
    ```

2. If you use **Linux**, then

    ```
    cd drupal_reactjs_boilerplate
    make prepare
    ```
    
    This will generate local environment files. Edit the created `.env` file in the root of the project and uncomment `PHP_TAG` version for Linux. And finally
    
    ```
    make install
    ```

3. That's it, the decoupled application is ready! 

# Access the applications

- [http://app.docker.localhost](http://app.docker.localhost) - React.js application
- [http://drupal.docker.localhost](http://drupal.docker.localhost) - Drupal 8 backend. Login credentials are `admin` / `admin`.

# Demo content

This boilerplate comes ready for the development by default, therefore all demo content is disabled. However, if you want to explore demo content from Contenta CMS, you need to enable `Recipes Magazin` module:

- `make drush en recipes_magazin`
- `make drush cr` (that's not needed usually, but JSON API endpoints becomes not available after module's install. Cache rebuild fixes the problem.)

Note that the frontend users fetching request will become broken in this case, because the enabled module slightly changes the structure of API endpoints.
To fix it, open `./reactjs/api/user.js` file and replace `.get('/api/user/user')` with `.get('/api/users')`.
