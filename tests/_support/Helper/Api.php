<?php

namespace Helper;

class Api extends \Codeception\Module {

  public function getTestPassword() {
    return getenv('TEST_USERS_PASSWORD');
  }
}
