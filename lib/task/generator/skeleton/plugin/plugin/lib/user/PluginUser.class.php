<?php

/**
 * This file declare the ##PLUGIN_NAME##User class.
 *
 * @package     ##PLUGIN_NAME##
 * @subpackage  user
 * @author      ##AUTHOR_NAME##
 * @version     SVN: $Id$
 */

/**
 * static methods used to register ##PLUGIN_NAME## user function
 */
class ##PLUGIN_NAME##User
{
  /**
   * listen to user.method_not_found event and call plugin function 
   * if exists.
   * this method is set up in ##PLUGIN_NAME##Configuration::initialize
   *
   * @param sfEvent $event the user.method_not_found event.
   */
  public static function methodNotFound(sfEvent $event)
  {
    if (method_exists('##PLUGIN_NAME##User', $event['method']))
    {
      $event->setReturnValue(call_user_func_array(
        array('##PLUGIN_NAME##User', $event['method']),
        array_merge(array($event->getSubject()), $event['arguments'])
      ));
      return true;
    }
  }

  /* define here your user methods. */
}
