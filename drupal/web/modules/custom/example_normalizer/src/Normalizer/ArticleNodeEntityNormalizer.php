<?php

namespace Drupal\example_normalizer\Normalizer;

use Drupal\serialization\Normalizer\ContentEntityNormalizer;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\node\NodeInterface;

/**
 * Converts the Drupal entity object structures to a normalized array.
 */
class ArticleNodeEntityNormalizer extends ContentEntityNormalizer {
  /**
   * The interface or class that this Normalizer supports.
   *
   * @var string
   */
  protected $supportedInterfaceOrClass = 'Drupal\node\NodeInterface';

  public function __construct(ContentEntityNormalizer $content_entity_normalizer, LinkManager $link_manager, ResourceTypeRepositoryInterface $resource_type_repository, EntityTypeManagerInterface $entity_type_manager) {
    $this->contentEntityNormalizer = $content_entity_normalizer;
    parent::__construct($link_manager, $resource_type_repository, $entity_type_manager);
  }
  /**
   * {@inheritdoc}
   */
  // public function supportsNormalization($data, $format = NULL) {
  //   // If we aren't dealing with an object or the format is not supported return
  //   // now.
  //   if (!is_object($data) || !$this->checkFormat($format)) {
  //     return FALSE;
  //   }
  //   // This custom normalizer should be supported for "Article" nodes.
  //   if ($data instanceof NodeInterface && $data->getType() == 'article') {
  //     return TRUE;
  //   }
  //   // Otherwise, this normalizer does not support the $data object.
  //   return FALSE;
  // }

  public function normalize($entity, $format = NULL, array $context = []) {
    $attributes = parent::normalize($entity, $format, $context);
    
    $attributes['eediturl'] = $entity->toUrl('edit-form', ['absolute' => TRUE])->toString();
    return $attributes;
  }
  
  protected function getFields($entity, $bundle, ResourceType $resource_type) {
    $output = parent::getFields($entity, $bundle, $resource_type);
    $link = $entity -> toUrl('edit-form', ['absolute' => TRUE]) -> toString();
    $listDefinition = \Drupal::typedDataManager()->createListDataDefinition('uri');
    $urls = \Drupal::typedDataManager()->create($listDefinition, [$link]);
    
    $output['editlink'] = $urls;
    return $output;
  }
  
}


