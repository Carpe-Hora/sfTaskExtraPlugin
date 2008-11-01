<?php

/**
 * Base extra class.
 * 
 * @package     sfTaskExtraPlugin
 * @subpackage  task
 * @author      Kris Wallsmith <kris.wallsmith@symfony-project.com>
 * @version     SVN: $Id$
 */
abstract class sfTaskExtraAddon extends sfTaskExtraBaseTask
{
  protected
    $wrappedTask = null;

  /**
   * Executes the extra.
   * 
   * @param   array $arguments
   * @param   array $options
   * 
   * @return  integer
   */
  abstract public function executeAddon($arguments = array(), $options = array());

  /**
   * @see sfTask
   */
  public function execute($arguments = array(), $options = array())
  {
    throw new sfException('You can\'t execute this task.');
  }

  /**
   * Sets the task the current extra wraps.
   * 
   * @param sfTask $task
   */
  public function setWrappedTask(sfTask $task)
  {
    $this->wrappedTask = $task;
  }

  /**
   * @see sfTask
   */
  public function log($messages)
  {
    is_null($this->wrappedTask) ? parent::log($message) : $this->wrappedTask->log($message);
  }

  /**
   * @see sfTask
   */
  public function logSection($section, $message, $size = null, $style = 'INFO')
  {
    is_null($this->wrappedTask) ? parent::logSection($section, $message, $size, $style) : $this->wrappedTask->log($section, $message, $size, $style);
  }

  /**
   * @see sfBaseTask
   */
  public function getFilesystem()
  {
    return $this->wrappedTask instanceof sfBaseTask ? $this->wrappedTask->getFilesystem() : new sfFilesystem();
  }
}
