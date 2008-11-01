<?php

/**
 * Wraps the generate module task to create a plugin module
 * 
 * @package     package
 * @subpackage  subpackage
 * @author      Kris Wallsmith
 * @version     SVN: $Id$
 */
class sfGenerateModuleAddon extends sfTaskExtraAddon
{
  /**
   * @see sfTaskExtraAddon
   */
  public function executeAddon($arguments = array(), $options = array())
  {
    $plugin = $arguments['application'];
    $module = $arguments['module'];

    // Validate the module name
    if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $module))
    {
      throw new sfCommandException(sprintf('The module name "%s" is invalid.', $module));
    }

    $moduleDir = sfConfig::get('sf_plugins_dir').'/'.$plugin.'/modules/'.$module;

    if (is_dir($moduleDir))
    {
      throw new sfCommandException(sprintf('The module "%s" already exists in the "%s" plugin.', $moduleDir, $plugin));
    }

    $properties = parse_ini_file(sfConfig::get('sf_config_dir').'/properties.ini', true);

    $constants = array(
      'PROJECT_NAME' => $plugin,
      'PLUGIN_NAME'  => $plugin,
      'MODULE_NAME'  => $module,
      'AUTHOR_NAME'  => isset($properties['symfony']['author']) ? $properties['symfony']['author'] : 'Your name here',
    );

    $extend = true;
    if (is_readable(sfConfig::get('sf_data_dir').'/skeleton/plugin/module'))
    {
      $skeletonDir = sfConfig::get('sf_data_dir').'/skeleton/plugin/module';
      $extend = false;
    }
    else if (is_readable(sfConfig::get('sf_data_dir').'/skeleton/module'))
    {
      $skeletonDir = sfConfig::get('sf_data_dir').'/skeleton/module';
    }
    else
    {
      $skeletonDir = sfConfig::get('sf_symfony_lib_dir').'/lib/task/generator/skeleton/module';
    }

    // create basic module structure
    $finder = sfFinder::type('any')->discard('.sf');
    $this->getFilesystem()->mirror($skeletonDir.'/module', $moduleDir, $finder);

    if ($extend)
    {
      $extensionDir = dirname(__FILE__).'/../generator/skeleton/plugin/module';

      // replace actions class
      $this->getFilesystem()->copy($extensionDir.'/actions/actions.class.php', $moduleDir.'/actions/actions.class.php', array('override' => true));

      // add base actions class
      $this->getFilesystem()->copy($extensionDir.'/lib/BaseActions.class.php', $moduleDir.'/lib/Base'.$module.'Actions.class.php');
    }

    // customize php and yml files
    $finder = sfFinder::type('file')->name('*.php', '*.yml');
    $this->getFilesystem()->replaceTokens($finder->in($moduleDir), '##', '##', $constants);
  }
}
