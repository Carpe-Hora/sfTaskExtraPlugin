<?php

/**
 * sfTaskExtraBaseTask tests.
 */
include dirname(__FILE__).'/../../bootstrap/unit.php';

$task = new sfCacheClearTask($configuration->getEventDispatcher(), new sfFormatter());
$task->setConfiguration($configuration);

$t = new lime_test(5);

// ::checkPluginExists()
$t->diag('::checkPluginExists()');

try
{
  sfTaskExtraBaseTask::checkPluginExists($task, 'NonexistantPlugin');
  $t->fail('::checkPluginExists() throws an exception if the plugin does not exist');
}
catch (Exception $e)
{
  $t->pass('::checkPluginExists() throws an exception if the plugin does not exist');
}

try
{
  sfTaskExtraBaseTask::checkPluginExists($task, 'NonexistantPlugin', false);
  $t->pass('::checkPluginExists() does not throw an excpetion if a plugin does not exists and is passed false');
}
catch (Exception $e)
{
  $t->fail('::checkPluginExists() does not throw an excpetion if a plugin does not exists and is passed false');
  $t->diag('    '.$e->getMessage());
}

try
{
  sfTaskExtraBaseTask::checkPluginExists($task, 'StandardPlugin');
  $t->pass('::checkPluginExists() does not throw an exception if a plugin exists');
}
catch (Exception $e)
{
  $t->fail('::checkPluginExists() does not throw an exception if a plugin exists');
  $t->diag('    '.$e->getMessage());
}

try
{
  sfTaskExtraBaseTask::checkPluginExists($task, 'StandardPlugin', false);
  $t->fail('::checkPluginExists() throws an exception if a plugin exists and is passed false');
}
catch (Exception $e)
{
  $t->pass('::checkPluginExists() throws an exception if a plugin exists and is passed false');
}

try
{
  sfTaskExtraBaseTask::checkPluginExists($task, 'SpecialPlugin');
  $t->pass('::checkPluginExists() does not throw a plugin is enabled with a special path');
}
catch (Exception $e)
{
  $t->fail('::checkPluginExists() does not throw a plugin is enabled with a special path');
  $t->diag('    '.$e->getMessage());
}
