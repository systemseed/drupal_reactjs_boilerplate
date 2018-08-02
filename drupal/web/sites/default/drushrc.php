<?php
// NOTE: this code is specific to project's original_url.
// If you want to reuse it on another project please review the code first.
if (!empty($_ENV['PLATFORM_ROUTES'])) {
  $platform_routes = json_decode(base64_decode($_ENV['PLATFORM_ROUTES']), TRUE);
  foreach ($platform_routes as $url => $route) {
    if ($route['original_url'] === 'https://admin.{default}/') {
      // Specify the base_url that should be used when generating links.
      $options['l'] = rtrim($url, '/');
    }
  }
}
