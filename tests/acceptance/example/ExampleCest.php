<?php

class ExampleCest {

  /**
   * Check that React application front page is accessible and
   * renders content fetched from Drupal.
   *
   * @param \AcceptanceTester $I
   */
  public function viewFrontend(AcceptanceTester $I) {
    $I->amGoingTo('Make sure that React.js application is working');
    $I->amOnPage('/');
    $I->waitForText('Home page is working!');

    $I->wantTo('Make sure articles user data can be fetched from Drupal');

    // Get article from Drupal backend.
    $articles = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->loadByProperties(['type' => 'article']);
    $article = array_shift($articles);
    $I->waitForText($article->label());

    $I->wantTo('Make sure that navigating to Home works');
    $I->click('a.nav-link[href="/"]');
    $I->waitForText($article->label());
  }

  /**
   * Check that backend is accessible and user can log in.
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
    $I->amOnUrl($backend_url . '/user');
    $I->see('Log in');

    // You can use Drupal API and database connection here as well!
    $account = \Drupal\user\Entity\User::load(1);

    $I->fillField('input[name="name"]', $account->getAccountName());
    $I->fillField('input[name="pass"]', 'admin');
    $I->click('input[type="submit"]');
    $I->see('Log out');
  }
}
