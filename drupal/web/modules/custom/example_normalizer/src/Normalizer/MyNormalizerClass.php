<?php

namespace Drupal\example_normalizer\Normalizer;
use Drupal\serialization\Normalizer\TypedDataNormalizer;
/**
 * {@inheritdoc}
 */
class MyNormalizerClass extends TypedDataNormalizer {
/**
   * {@inheritdoc}
   */
  public function normalize($entity, $format = NULL, array $context = []) {
    $data = parent::normalize($entity, $format, $context);
    // transform your data here
    // You'll likely need to run some checks on the $entity or $data
    // variables and include conditionals so that only the items
    // you are interested in are altered
    $data['editlink']='editlink-testing';
    return $data;
  }
}