<?php
// Make sure the platform.sh has env:HTTP_AUTH_USER and env:HTTP_AUTH_PASS
// variables set.
if (empty($_ENV['HTTP_AUTH_USER']) || empty($_ENV['HTTP_AUTH_PASS'])) {
  return;
}
// Ignore drush requests.
if (PHP_SAPI == 'cli') {
  return;
}
// Some browsers like EDGE or Safari replace used Bearer authentication with Basic
// In this case we grab accessToken from cookies and set correct Authentication header with Bearer.
$is_frontend_request = substr($GLOBALS['request']->server->get('REQUEST_URI'),0, 7) !== '/admin/';
if ($is_frontend_request &&
  strpos($GLOBALS['request']->headers->get('authorization'), 'Basic') !== false &&
  !empty($GLOBALS['request']->headers->get('cookie'))) {
  // Check if cookie header contains accessToken to use for authentication.
  // An example of Cookie header data: `accessToken=aaaaaaa;refreshToken=bbbbbb'.
  $cookie_data = explode(';', $GLOBALS['request']->headers->get('cookie'));
  if (!empty($cookie_data)) {
    foreach ($cookie_data as $cookie_data_item) {
      $cookie_row_data = explode('=', trim($cookie_data_item));
      if (!empty($cookie_row_data[0]) && $cookie_row_data[0] == 'accessToken') {
        $GLOBALS['request']->headers->set('authorization', 'Bearer ' . $cookie_row_data[1]);
        break;
      }
    }
  }
}
// If the request includes custom http-auth header, then validate it.
// This header was introduced to send requests from node.js server to the
// backend without http authentication.
$http_auth = $GLOBALS['request']->headers->get('http-auth');
if (!empty($http_auth)) {
  if ($http_auth == $_ENV['HTTP_AUTH_USER'] . ':' . $_ENV['HTTP_AUTH_PASS']) {
    return;
  }
}
// If this is the request from the same host, do not require http auth.
// The only way to get into the web site is to input http auth credentials,
// so we remove them for all internal requests within the site.
$referer = $GLOBALS['request']->server->get('HTTP_REFERER');
if (!empty($referer)) {
  $referer_url = parse_url($referer);
  if ($referer_url['host'] == $GLOBALS['request']->server->get('HTTP_HOST')) {
    return;
  }
}
// Get basic auth credentials from web browser.
$username = !empty($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
$password = !empty($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
// Show http auth if user and pass don't match variables in platform.sh.
if (!($username == $_ENV['HTTP_AUTH_USER'] && $password == $_ENV['HTTP_AUTH_PASS'])) {
  header('WWW-Authenticate: Basic realm="Restricted Page"');
  header('HTTP/1.0 401 Unauthorized');
  die();
}
