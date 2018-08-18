<?php

/**
 * @file
 * Platform.sh settings.
 */

// Configure the database.
if (isset($_ENV['PLATFORM_RELATIONSHIPS'])) {
  $relationships = json_decode(base64_decode($_ENV['PLATFORM_RELATIONSHIPS']), TRUE);
  if (!empty($relationships['database'])) {
    foreach ($relationships['database'] as $endpoint) {
      $database = [
        'driver' => $endpoint['scheme'],
        'database' => $endpoint['path'],
        'username' => $endpoint['username'],
        'password' => $endpoint['password'],
        'host' => $endpoint['host'],
        'port' => $endpoint['port'],
      ];
      if (!empty($endpoint['query']['compression'])) {
        $database['pdo'][PDO::MYSQL_ATTR_COMPRESS] = TRUE;
      }
      if (!empty($endpoint['query']['is_master'])) {
        $databases['default']['default'] = $database;
      }
      else {
        $databases['default']['replica'][] = $database;
      }
    }
  }

  // @see platform.sh solr documentation: https://docs.platform.sh/frameworks/drupal8/solr.html
  if (!empty($relationships['solr'][0])) {
    // Edit this value to use the the machine name of the Solr server in Drupal
    // if you are using a different server than the default one automatically
    // created by the module Search API Solr, which is named 'default_solr_server'.
    $solr_server_name = 'solr_server';
    $solr = $relationships['solr'][0];
    // Gets the name of the Solr core from the Platform.sh relationship. Uses
    // 'collection1' if empty to conform with the default Solr service single
    // core configuration for versions lower than 6.x.
    $core = substr($solr['path'], 5) ? : 'collection1';
    $config['search_api.server.' . $solr_server_name]['backend_config']['connector_config']['core'] = $core;
    // The path is always 'solr'.
    $config['search_api.server.' . $solr_server_name]['backend_config']['connector_config']['path'] = '/solr';
    // Gets the host and port from the values returned from the relationship.
    $config['search_api.server.' . $solr_server_name]['backend_config']['connector_config']['host'] = $solr['host'];
    $config['search_api.server.' . $solr_server_name]['backend_config']['connector_config']['port'] = $solr['port'];
  }
}

if (isset($_ENV['PLATFORM_APP_DIR'])) {
  // Configure private and temporary file paths.
  if (!isset($settings['file_private_path'])) {
    $settings['file_private_path'] = $_ENV['PLATFORM_APP_DIR'] . '/private';
  }
  if (!isset($config['system.file']['path']['temporary'])) {
    $config['system.file']['path']['temporary'] = $_ENV['PLATFORM_APP_DIR'] . '/tmp';
  }
  // Configure the default PhpStorage and Twig template cache directories.
  if (!isset($settings['php_storage']['default'])) {
    $settings['php_storage']['default']['directory'] = $settings['file_private_path'];
  }
  if (!isset($settings['php_storage']['twig'])) {
    $settings['php_storage']['twig']['directory'] = $settings['file_private_path'];
  }
}

// Set trusted hosts based on Platform.sh routes.
if (isset($_ENV['PLATFORM_ROUTES']) && !isset($settings['trusted_host_patterns'])) {
  $routes = json_decode(base64_decode($_ENV['PLATFORM_ROUTES']), TRUE);
  $settings['trusted_host_patterns'] = [];
  foreach ($routes as $url => $route) {
    $host = parse_url($url, PHP_URL_HOST);
    if ($host !== FALSE && $route['type'] == 'upstream' && $route['upstream'] == $_ENV['PLATFORM_APPLICATION_NAME']) {
      $settings['trusted_host_patterns'][] = '^' . preg_quote($host) . '$';
    }
    // Defines Frontend application domain.
    if ($route['type'] == 'upstream' && !empty($route['original_url']) && $route['original_url'] == 'https://{default}/') {
      $settings['frontend_domain'] = $url;
    }
  }
  $settings['trusted_host_patterns'] = array_unique($settings['trusted_host_patterns']);
}

// Import variables prefixed with 'd8settings:' into $settings and 'd8config:'
// into $config.
if (isset($_ENV['PLATFORM_VARIABLES'])) {
  $variables = json_decode(base64_decode($_ENV['PLATFORM_VARIABLES']), TRUE);
  foreach ($variables as $name => $value) {
    // A variable named "d8settings:example-setting" will be saved in
    // $settings['example-setting'].
    if (strpos($name, 'd8settings:') === 0) {
      $settings[substr($name, 11)] = $value;
    }
    // A variable named "drupal:example-setting" will be saved in
    // $settings['example-setting'] (backwards compatibility).
    elseif (strpos($name, 'drupal:') === 0) {
      $settings[substr($name, 7)] = $value;
    }
    // A variable named "d8config:example-name:example-key" will be saved in
    // $config['example-name']['example-key'].
    elseif (strpos($name, 'd8config:') === 0 && substr_count($name, ':') >= 2) {
      list(, $config_key, $config_name) = explode(':', $name, 3);
      $config[$config_key][$config_name] = $value;
    }
    // A complex variable named "d8config:example-name" will be saved in
    // $config['example-name'].
    elseif (strpos($name, 'd8config:') === 0 && is_array($value)) {
      $config[substr($name, 9)] = $value;
    }
  }
}

// Set the project-specific entropy value, used for generating one-time
// keys and such.
if (isset($_ENV['PLATFORM_PROJECT_ENTROPY'])) {
  $settings['hash_salt'] = $_ENV['PLATFORM_PROJECT_ENTROPY'];
}
