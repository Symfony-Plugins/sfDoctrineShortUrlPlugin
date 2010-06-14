<?php

/**
 * Routing configuration
 *
 * @package    sfDoctrineShortUrlPlugin
 * @subpackage routing
 * @author     Xavier Lacot <xavier@lacot.org>
 * @see        http://www.symfony-project.org/plugins/sfDoctrineShortUrlPlugin
 */
class sfDoctrineShortUrlRouting
{
  /**
   * Listens to the routing.load_configuration event.
   *
   * @param sfEvent An sfEvent instance
   */
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $r = $event->getSubject();
    $r->prependRoute(
      'sfShorturl',
      new sfRoute(
        '/:shorturl',
        array('module' => 'sfShortUrl', 'action' => 'shorturl')
      )
    );
    $r->prependRoute(
      'sfShorturl_homepage',
      new sfRoute(
        '/',
        array('module' => 'sfShortUrl', 'action' => 'index')
      )
    );
    $r->prependRoute(
      'sfShorturl_decode',
      new sfRoute(
        '/decode',
        array('module' => 'sfShortUrl', 'action' => 'decode')
      )
    );
  }
}
