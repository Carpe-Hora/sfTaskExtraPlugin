<?php

/**
 * Plugin generator base task.
 * 
 * @package     sfTaskExtraPlugin
 * @subpackage  task
 * @author      Kris Wallsmith <kris.wallsmith@symfony-project.com>
 * @version     SVN: $Id$
 */
abstract class sfTaskExtraGeneratorBaseTask extends sfGeneratorBaseTask
{
  /**
   * @see sfTaskExtraBaseTask
   */
  protected function checkPluginExists($plugin)
  {
    return sfTaskExtraBaseTask::checkPluginExists($plugin);
  }
}
