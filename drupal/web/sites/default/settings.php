<?php

// @codingStandardsIgnoreFile

/**
 * Default Drupal settings.
 */
$settings['update_free_access'] = FALSE;
$settings['entity_update_batch_size'] = 50;
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';
$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
];

$settings['install_profile'] = 'contenta_jsonapi';
$config_directories[CONFIG_SYNC_DIRECTORY] = '../config/sync';

// Default development settings.
// They are overriden in settings.env_production.php.
$config['system.logging']['error_level'] = 'verbose';
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;

// Default hash salt. Suitable only for local environment. It gets overriden
// in settings.platformsh.php for all Platform.sh environments.
$settings['hash_salt'] = 'insecure-local-hash';

// Default (local) connection to the database. Gets overriden in
// settings.platformsh.php for all Platform.sh environments.
$databases['default']['default'] = [
  'database' => 'drupal',
  'username' => 'drupal',
  'password' => 'drupal',
  'prefix' => '',
  'host' => 'mariadb',
  'port' => '',
  'driver' => 'mysql',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'collation' => 'utf8mb4_general_ci',
];

/**
 * Settings for Platform.sh environments.
 */
if (!empty($_ENV['PLATFORM_BRANCH'])) {
  // Include Platform.sh specific configs to connect
  // Drupal to Platform.sh servers.
  require_once __DIR__ . '/settings.platformsh.php';
  if ($_ENV['PLATFORM_BRANCH'] == 'master') {
    // Include production-only configs which override
    // development settings.
    require_once __DIR__ . '/settings.env_production.php';
  }
  else {
    // Enable http authentication on non-production branches.
    require_once __DIR__ . '/http_auth.php';
  }
}
// Local settings. These come last so that they can override anything.
else {
  require_once __DIR__ . '/settings.env_local.php';
}
