<?php

/**
 * This file declare the ##PLUGIN_NAME##Routing class.
 *
 * @package     ##PLUGIN_NAME##
 * @subpackage  routing
 * @author      ##AUTHOR_NAME##
 * @version     SVN: $Id$
 */

/**
 * static methods used to register ##PLUGIN_NAME## routes
 */
class ##PLUGIN_NAME##Routing
{
  /**
   * Listens to the routing.load_configuration event.
   *
   * @param sfEvent An sfEvent instance
   */
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    foreach (array(/* list your modules here */) as $module)
    {
      if (in_array($module, sfConfig::get('sf_enabled_modules')))
      {
        call_user_func(array('##PLUGIN_NAME##Routing',sprintf('prepend%sRoutes', ucfirst($module))), $event->getSubject());
      }
    }
  }

  /* define your prependMyModule($routing) methods to register routes */
}
