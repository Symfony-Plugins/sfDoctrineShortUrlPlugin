<?php
/*
 * This file is part of the sfDoctrineShortUrlPlugin package.
 *
 * (c) 2009 Xavier Lacot <xavier@lacot.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// add routing rules
if (sfConfig::get('app_sfDoctrineShortUrlPlugin_use_routes', true)
    && in_array('sfShortUrl', sfConfig::get('sf_enabled_modules', array())))
{
  $this->dispatcher->connect(
    'routing.load_configuration',
    array('sfDoctrineShortUrlRouting', 'listenToRoutingLoadConfigurationEvent')
  );
}