<?php

/**
 * Plugin configuration.
 * 
 * @package     sfTaskExtraPlugin
 * @subpackage  config
 * @author      Kris Wallsmith <kris.wallsmith@symfony-project.com>
 * @version     SVN: $Id$
 */
class sfTaskExtraPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function configure()
  {
    $this->dispatcher->connect('command.filter_options', array($this, 'filterCommandOptions'));
    $this->dispatcher->connect('command.pre_command', array($this, 'listenForPreCommand'));
    $this->dispatcher->connect('command.post_command', array($this, 'listenForPostCommand'));
  }

  /**
   * Filters command options.
   * 
   * @param   sfEvent $event
   * @param   array   $options
   * 
   * @return  array
   */
  public function filterCommandOptions(sfEvent $event, $options)
  {
    $task = $event->getSubject();
    $commandManager = $event['command_manager'];

    return $options;
  }

  /**
   * Listens for the 'command.pre_command' event.
   * 
   * @param   sfEvent $event
   * 
   * @return  boolean
   */
  public function listenForPreCommand(sfEvent $event)
  {
    $task = $event->getSubject();
    $arguments = $event['arguments'];
    $options = $event['options'];

    if ($task instanceof sfGenerateModuleTask && 'Plugin' == substr($arguments['application'], -6))
    {
      $addon = new sfGenerateModuleAddon($this->dispatcher, new sfAnsiColorFormatter());
      $addon->setWrappedTask($task);
      $ret = $addon->executeAddon($arguments, $options);

      $event->setReturnValue($ret);
      return true;
    }

    return false;
  }

  public function listenForPostCommand(sfEvent $event)
  {
    $task = $event->getSubject();
  }
}
