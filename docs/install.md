# Installation

1. To get everything installed locally, simply use the following commands:

    ```
    cd drupal_reactjs_boilerplate
    make install
    ```

2. That's it, the Decoupled application is ready for development! 

# Access the applications

- [http://app.docker.localhost](http://app.docker.localhost) - React.js application
- [http://drupal.docker.localhost](http://drupal.docker.localhost) - Drupal 8 backend. Login credentials are `admin` / `admin`.

# Demo content

This boilerplate comes as an example of integration between frontend and backend applications, therefore all demo content is enabled by default. However, if you want to get rid of it and focus on development - all you need to do is to delete the module with demo content by calling `make drush pmu recipes_magazin`.
