<?php

/**
 * Generates a new plugin.
 * 
 * @package     sfTaskExtraPlugin
 * @subpackage  task
 * @author      Kris Wallsmith <kris.wallsmith@symfony-project.com>
 * @version     SVN: $Id$
 */
class sfGeneratePluginTask extends sfGeneratorBaseTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('plugin', sfCommandArgument::REQUIRED, 'The plugin name'),
    ));

    $this->addOptions(array(
      new sfCommandOption('config', null, sfCommandOption::PARAMETER_NONE, 'Add a config directory'),
      new sfCommandOption('data', null, sfCommandOption::PARAMETER_NONE, 'Add a data directory'),
      new sfCommandOption('lib', null, sfCommandOption::PARAMETER_NONE, 'Add a lib directory'),
      new sfCommandOption('module', null, sfCommandOption::PARAMETER_REQUIRED | sfCommandOption::IS_ARRAY, 'Add a module'),
    ));

    $this->aliases = array('init-plugin');
    $this->namespace = 'generate';
    $this->name = 'plugin';

    $this->briefDescription = 'Generates a new plugin';

    $this->detailedDescription = <<<EOF
The [generate:plugin|INFO] task creates the basic directory structure for a new
plugin in the current project:

  [./symfony generate:plugin sfExamplePlugin|INFO]
EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
  }
}
