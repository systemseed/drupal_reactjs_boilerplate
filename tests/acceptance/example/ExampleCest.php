<?php

class ExampleCest {

  /**
   * Check that front page is accessible from test suite.
   *
   * @param \AcceptanceTester $I
   */
  public function viewFrontend(AcceptanceTester $I) {
    $I->amGoingTo('Make sure that React.js application is working');
    $I->amOnPage('/');
    $I->see('Home page is working!');

    $I->wantTo('Make sure admin user data was pulled from Drupal');
    $I->see('admin');
  }

  /**
   * Check that backend is accessible from the test suite.
   *
   * @param \AcceptanceTester $I
   */
  public function viewBackend(AcceptanceTester $I) {
    try {
      $backend_url = $I->getBackendURL();
    } catch (\Exception $exception) {
      $backend_url = '';
    }

    $I->amGoingTo('Log in on the backend as administrator');
    $I->amOnUrl($backend_url);
    $I->see('Log in');
  }
}
