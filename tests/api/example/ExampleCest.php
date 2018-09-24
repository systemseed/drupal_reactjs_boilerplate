<?php

namespace jsonapi;

use Codeception\Util\HttpCode;

/**
 * Class ExampleCest.
 *
 * Example API test with database connection.
 *
 * @package Jsonapi
 */
class ExampleCest {

  /**
   * Test /jsonapi endpoint for demo purposes.
   *
   * @param \ApiTester $I
   */
  public function accessJsonapiIndex(\ApiTester $I) {
    $path_prefix = \Drupal::config('jsonapi_extras.settings')
      ->get('path_prefix');
    $I->amGoingTo('Send not authenticated request to the index endpoint.');
    $I->sendGET("/$path_prefix");
    $I->expectTo('See an error.');
    $I->dontSeeResponseCodeIs(HttpCode::OK);
    $I->amGoingTo('Login as administrator.');
    $this->loginAsAdministrator($I);
    $I->amGoingTo('Send request to the index endpoint as an administrator.');
    $I->sendGET("/$path_prefix");
    $I->expectTo('Successful response.');
    $I->seeResponseCodeIs(HttpCode::OK);
  }

  /**
   * Internal.
   *
   * Test core login endpoint for demo purposes.
   *
   * @param \ApiTester $I
   */
  private function loginAsAdministrator(\ApiTester $I) {
    $username = 'admin';
    $password = 'admin';

    // Fetch user id from database.
    $uid = $I->grabFromDatabase(
      'users_field_data',
      'uid',
      ['name' => $username]
    );
    // Fail if user is not found.
    $I->assertNotEmpty($uid, 'Test admin uid should not be empty');
    // Login using Drupal core endpoint. It will set session cookie.
    $I->haveHttpHeader('Content-Type', 'application/json');
    $I->sendPOST(
      '/user/login?_format=json',
      [
        'name' => $username,
        'pass' => $password,
      ]
    );
    $I->seeResponseCodeIs(HttpCode::OK);
    // Check if specific key is presented in the response.
    $I->seeResponseJsonMatchesJsonPath('$.csrf_token');
    // Check if specific data is presented in the response.
    $I->seeResponseContainsJson(
      [
        'current_user' => [
          'uid' => $uid,
          'roles' => ['authenticated', 'administrator']
        ]
      ]
    );
  }
}
