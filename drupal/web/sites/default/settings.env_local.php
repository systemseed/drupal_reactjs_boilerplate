<?php

// @codingStandardsIgnoreFile

assert_options(ASSERT_ACTIVE, TRUE);
\Drupal\Component\Assertion\Handle::register();

$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';

$settings['rebuild_access'] = TRUE;
$settings['skip_permissions_hardening'] = TRUE;

// See https://www.drupal.org/node/1992030
$settings['trusted_host_patterns'] = [
  'docker\.localhost$',
  'docker\.local$', # Tests specific case.
];

// Set writable folder for temp file storage.
$config['system.file']['path']['temporary'] = '/tmp';

// Disable SMTP usage on all local environments.
$config['smtp.settings']['smtp_on'] = FALSE;

// Enable private file system locally.
$settings['file_private_path'] = DRUPAL_ROOT . '/sites/default/files/private';
