# Tests coverage

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
