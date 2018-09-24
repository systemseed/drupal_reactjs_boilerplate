<?php

use Drupal\user\Entity\User;

class ExampleTest extends \Codeception\Test\Unit {

  /**
   * @var \UnitTester
   */
  protected $tester;

  /**
   * Checks that admin has password "admin".
   */
  public function testAdminHasCorrectPassword() {
    /* @var $password_hasher Drupal\Core\Password\PhpassHashedPassword */
    $password_hasher = \Drupal::service('password');
    $account = User::load(1);
    $passwords_equal = $password_hasher->check('admin', $account->getPassword());
    $this->assertEquals(TRUE, $passwords_equal, 'Admin password is set to "admin"');
  }
}
