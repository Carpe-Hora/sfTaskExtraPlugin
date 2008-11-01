<?php

/**
 * Plugin base task.
 * 
 * @package     sfTaskExtraPlugin
 * @subpackage  task
 * @author      Kris Wallsmith <kris.wallsmith@symfony-project.com>
 * @version     SVN: $Id$
 */
abstract class sfTaskExtraBaseTask extends sfBaseTask
{
  /**
   * Checks if a plugin exists.
   * 
   * @param   string $plugin
   * 
   * @throws  sfException If the plugin does not exist
   */
  public function checkPluginExists($plugin)
  {
    if (!is_dir(sfConfig::get('sf_plugins_dir').'/'.$plugin))
    {
      throw new sfException(sprintf('Plugin "%s" does not exist', $plugin));
    }
  }
}
