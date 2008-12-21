<?php

/**
 * Generates unit test stub scripts.
 * 
 * @package     sfTaskExtraPlugin
 * @subpackage  task
 * @author      Kris Wallsmith <kris.wallsmith@symfony-project.com>
 * @version     SVN: $Id$
 */
class sfGenerateTestsTask extends sfTaskExtraGeneratorBaseTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('class', sfCommandArgument::OPTIONAL, 'The class to test'),
    ));

    $this->addOptions(array(
      new sfCommandOption('dir', null, sfCommandOption::PARAMETER_REQUIRED | sfCommandOption::IS_ARRAY, 'The subdirectory to search for classes in'),
      new sfCommandOption('exclude', null, sfCommandOption::PARAMETER_REQUIRED | sfCommandOption::IS_ARRAY, 'Directory to exclude'),
    ));

    $this->namespace = 'generate';
    $this->name = 'tests';

    $this->briefDescription = 'Generates unit test stub scripts';

    $this->detailedDescription = <<<EOF
The [generate:tests|INFO] task generates empty unit tests scripts in your
[test/unit/|COMMENT] directory and reflects the organization of your [lib/|COMMENT] directory:

  [./symfony generate:tests|INFO]

You can specify a particular class:

  [./symfony generate:tests Article|INFO]

As the task recurs through your [lib/|COMMENT] directory, you can specify subdirectories
to limit the scope of the task with the [--dir|COMMENT] option:

  [./symfony generate:tests --dir=form|INFO]

Alternatively, you can specify directories to exclude with the
[--exclude|COMMENT] option:

  [./symfony generate:tests --exclude=filter|INFO]
EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (!is_null($arguments['class']) && !preg_match('/^\w+$/', $arguments['class']))
    {
      throw new InvalidArgumentException(sprintf('The class name "%s" is not valid.', $arguments['class']));
    }

    $name  = $arguments['class'] ? sprintf('/^%s(?:\.class)?\.php$/', $arguments['class']) : '*.php';
    $prune = array_merge(array('symfony', 'om', 'map', 'base'), $options['exclude']);
    $dirs  = count($options['dir']) ? $options['dir'] : array('');

    $count = 0;

    $finder = sfFinder::type('file')->relative()->name($name)->prune($prune);
    foreach ($dirs as $dir)
    {
      foreach ($finder->in(sfConfig::get('sf_lib_dir').'/'.$dir) as $file)
      {
        if (preg_match('/^\w+/', basename($file), $match))
        {
          $path = $dir.(false === strpos($file, DIRECTORY_SEPARATOR) ? '' : ('/'.dirname($file)));
          $test = sfConfig::get('sf_test_dir').'/unit'.$path.'/'.$match[0].'Test.php';

          if (!file_exists($test))
          {
            $this->getFilesystem()->copy(dirname(__FILE__).'/skeleton/test/UnitTest.php', $test);
            $this->getFilesystem()->replaceTokens($test, '##', '##', array(
              'TEST_DIR' => str_repeat('/..', substr_count($path, DIRECTORY_SEPARATOR) + 1),
            ));

            $count++;
          }
        }
      }
    }

    $this->logSection('task', sprintf('Generated %s test stub(s)', number_format($count)));
  }
}
