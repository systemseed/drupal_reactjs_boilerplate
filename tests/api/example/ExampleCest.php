<?php

namespace jsonapi;

use Codeception\Util\HttpCode;
use Drupal\user\Entity\User;

/**
 * Class ExampleCest.
 *
 * Example API tests with database connection.
 */
class ExampleCest {

  /**
   * Test REST authentication for admin.
   *
   * @param \ApiTester $I
   */
  public function authenticateAdmin(\ApiTester $I) {

    $username = 'admin';
    $password = 'admin';

    // Fetch user id from database (you've got access to the database!).
    $uid = $I->grabFromDatabase(
      'users_field_data',
      'uid',
      ['name' => $username]
    );

    // Fail if user is not found.
    $I->assertNotEmpty($uid, 'Admin uid should not be empty');

    // Login using Drupal core endpoint. It will set session cookie.
    $I->haveHttpHeader('Content-Type', 'application/json');
    $I->sendPOST(
      '/user/login?_format=json',
      [
        'name' => $username,
        'pass' => $password,
      ]
    );

    $I->expectTo('See successful response.');
    $I->seeResponseCodeIs(HttpCode::OK);

    $I->expectTo('Check that specific key exists in the response.');
    $I->seeResponseJsonMatchesJsonPath('$.csrf_token');

    $I->expectTo('Check that specific data is presented in the response.');
    $I->seeResponseContainsJson(
      [
        'current_user' => [
          'uid' => $uid,
          'roles' => ['authenticated']
        ]
      ]
    );
  }

  /**
   * Checks that anonymous user can view users info.
   *
   * @param \ApiTester $I
   */
  /*public function accessJsonApiUserData(\ApiTester $I) {
    $path_prefix = \Drupal::config('jsonapi_extras.settings')
      ->get('path_prefix');

    $I->amGoingTo('Send request to the JSON API user endpoint.');
    $I->sendGET("/$path_prefix/user/user");

    $I->expectTo('See successful response.');
    $I->seeResponseCodeIs(HttpCode::OK);

    // Load admin user data from Drupal database and make sure it
    // exists in the response.
    $I->expectTo('Find admin user data in the response.');
    $account = User::load(1);
    $I->seeResponseContainsJson(
      [
        'attributes' => [
          'uid' => $account->id(),
          'name' => $account->getAccountName()
        ]
      ]
    );
  }*/
}
