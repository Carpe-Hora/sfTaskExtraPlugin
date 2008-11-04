<?php

/**
 * Generates a new plugin.
 * 
 * @package     sfTaskExtraPlugin
 * @subpackage  task
 * @author      Kris Wallsmith <kris.wallsmith@symfony-project.com>
 * @version     SVN: $Id$
 */
class sfGeneratePluginTask extends sfTaskExtraGeneratorBaseTask
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
      new sfCommandOption('module', null, sfCommandOption::PARAMETER_REQUIRED | sfCommandOption::IS_ARRAY, 'Add a module'),
    ));

    $this->namespace = 'generate';
    $this->name = 'plugin';

    $this->briefDescription = 'Generates a new plugin';

    $this->detailedDescription = <<<EOF
The [generate:plugin|INFO] task creates the basic directory structure for a
new plugin in the current project:

  [./symfony generate:plugin sfExamplePlugin|INFO]

You can customize the default skeleton used by the task by creating a
[%sf_data_dir%/skeleton/plugin|COMMENT] directory.

You can also specify one or more modules you would like included in this
plugin using the [--module|COMMENT] option:

  [./symfony generate:plugin sfExamplePlugin --module=sfExampleFoo --module=sfExampleBar|INFO]
EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    $plugin = $arguments['plugin'];
    $modules = $options['module'];

    // validate the plugin name
    if ('Plugin' != substr($plugin, -6) || !preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $plugin))
    {
      throw new sfCommandException(sprintf('The plugin name "%s" is invalid.', $plugin));
    }

    try
    {
      $this->checkPluginExists($plugin);
      throw new sfCommandException(sprintf('The plugin "%s" already exists.', $plugin));
    }
    catch (Exception $e)
    {
    }

    if (is_readable(sfConfig::get('sf_data_dir').'/skeleton/plugin'))
    {
      $skeletonDir = sfConfig::get('sf_data_dir').'/skeleton/plugin';
    }
    else
    {
      $skeletonDir = dirname(__FILE__).'/skeleton/plugin';
    }

    $pluginDir = sfConfig::get('sf_plugins_dir').'/'.$plugin;

    $properties = parse_ini_file(sfConfig::get('sf_config_dir').'/properties.ini', true);
    $constants = array(
      'PLUGIN_NAME' => $plugin,
      'AUTHOR_NAME' => isset($properties['symfony']['author']) ? $properties['symfony']['author'] : 'Your name here',
    );

    // copy basic plugin structure
    $finder = sfFinder::type('any')->discard('.sf');
    $this->getFilesystem()->mirror($skeletonDir, $pluginDir, $finder);

    // rename configuration file
    $this->getFilesystem()->rename($pluginDir.'/config/PluginConfiguration.class.php', $pluginDir.'/config/'.$plugin.'Configuration.class.php');

    // customize php and yml files
    $finder = sfFinder::type('file')->name('*.php', '*.yml');
    $this->getFilesystem()->replaceTokens($finder->in($pluginDir), '##', '##', $constants);

    // create modules
    foreach ($modules as $module)
    {
      $moduleTask = new sfGeneratePluginModuleTask($this->dispatcher, $this->formatter);
      $moduleTask->setCommandApplication($this->commandApplication);
      $moduleTask->run(array($plugin, $module));
    }
  }
}
