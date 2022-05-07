<?php

namespace Drupal\jsonapi_assessment\EventSubscriber;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\jsonapi\Routing\Routes;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ResponseSubscriber.
 *
 * Implements the alter hook for JSON:API responses.
 *
 * @package Drupal\jsonapi_assessment\EventSubscriber
 */
class ResponseSubscriber implements EventSubscriberInterface, ContainerInjectionInterface {

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The route match service.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * ResponseSubscriber constructor.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   *   The module handler.
   */
  public function __construct(ModuleHandlerInterface $moduleHandler) {
    $this->moduleHandler = $moduleHandler;
  }

  /**
   * Create a new instance.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container.
   *
   * @return \Drupal\jsonapi_assessment\EventSubscriber\ResponseSubscriber
   *   The new instance.
   */
  public static function create(ContainerInterface $container) {
    return new self(
      $container->get('module_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE] = ['onResponse'];

    return $events;
  }

  /**
   * Set route match service.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The  route match service.
   */
  public function setRouteMatch(RouteMatchInterface $route_match) {
    $this->routeMatch = $route_match;
  }

  /**
   * This method is called the KernelEvents::RESPONSE event is dispatched.
   *
   * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
   *   The filter event.
   */
  public function onResponse(FilterResponseEvent $event) {
    if (!$this->routeMatch->getRouteObject()) {
      return;
    }

    if (
      $this->routeMatch->getRouteName() === 'jsonapi.resource_list' ||
      Routes::isJsonApiRequest($this->routeMatch->getRouteObject()
        ->getDefaults())
    ) {

      $response = $event->getResponse();
      $content = $response->getContent();

      $jsonapi_response = json_decode($content, TRUE);

      if (!is_array($jsonapi_response)) {
        return;
      }

      // call the hook aler
      $this->moduleHandler->alter('jsonapi_response', $jsonapi_response, $response);
      $response->setContent(json_encode($jsonapi_response));
    }
  }

}
