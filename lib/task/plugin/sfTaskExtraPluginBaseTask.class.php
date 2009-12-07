<?php

/**
 * Plugin plugin base task.
 * 
 * @package     sfTaskExtraPlugin
 * @subpackage  task
 * @author      Kris Wallsmith <kris.wallsmith@symfony-project.com>
 * @version     SVN: $Id$
 */
abstract class sfTaskExtraPluginBaseTask extends sfPluginBaseTask
{
  /**
   * @see sfTaskExtraBaseTask
   */
  public function checkPluginExists($plugin, $boolean = true)
  {
    return sfTaskExtraBaseTask::doCheckPluginExists($this, $plugin, $boolean);
  }
}
