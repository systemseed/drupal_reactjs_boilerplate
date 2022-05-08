<?php

/**
 * @file
 * Hooks JSON:API alter.
 */

use Symfony\Component\HttpFoundation\Response;

/**
 * Alters the JSON:API response.
 *
 * @param array $jsonapi_response
 *   The decoded JSON data to be altered.
 * @param \Symfony\Component\HttpFoundation\Response $response
 *   The response.
 */
function hook_jsonapi_assessment_alter(array &$jsonapi_response, Response $response) {
    
}
  