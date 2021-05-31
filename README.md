[![CircleCI](https://circleci.com/gh/systemseed/drupal_reactjs_boilerplate.svg?style=svg)](https://circleci.com/gh/systemseed/drupal_reactjs_boilerplate)
[![ReadTheDocs](https://readthedocs.org/projects/drupal-reactjs-boilerplate/badge/?version=latest)](https://readthedocs.org/projects/drupal-reactjs-boilerplate/badge/?version=latest)

### What is this project for?

The main purpose of the project is to provide a super-quick start for fully decoupled applications development based on **Drupal 8** and **React**.

Our main goal was to create the boilerplate with minimal amount of opinionated set of features, but at the same time give developers a solid base for start to let them focus on the development and do not worry about infrastructure / integrations set up. 

### What does this project include?

We've got many little developers tricks and sugar added, but from the very high level the project includes:

- One-command installation experience for the whole development / testing infrastructure
- Docker configuration to avoid any necessity in third-party tools installed on your local machine
- Configured **Drupal 8** application based on [Contenta CMS](http://www.contentacms.org) for comprehensive decoupled development
- Configured **React.js** application based on [Next.js](https://nextjs.org/) for Server Side Rendering support and great dev experience
- 3 types of tests included (Unit / Integration, API, Acceptance) with examples, Drupal API integration & database connection
- [Platform.sh](https://platform.sh) ready configuration for those who want to run the project in web
- Simple commands for automated code quality checks / fixes
- Continuous Integration example for [CircleCI](https://circleci.com) to test project installation, code quality & run tests.

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


### Explore the documentation

- [Shorthand commands](docs/commands.md)
- [Features](docs/features.md)
- [Writing automated tests](docs/tests.md)
