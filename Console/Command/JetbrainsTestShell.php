<?php
App::uses('TestShell', 'Console/Command');
App::uses('JetbrainsTestSuiteCommand', 'CakeJetbrainsTest.TestSuite');
App::uses('JetbrainsTestLoader', 'CakeJetbrainsTest.TestSuite');

/**
 * Provides additional wrapper between CakePHP test runner
 * and JetBrains IDEs
 */
class JetbrainsTestShell extends TestShell {

    public function startup() {

    }

    /**
     * Overload parent to get control under $argv
     *
     * @param  string  $command
     * @param  array   $argv
     */
    public function runCommand($command, $argv) {
        $args = $this->parseArgsIDE($argv);

        parent::runCommand($args[0], $args);
    }

    /**
     * Runs the test case from $runnerArgs using custom command wrapper
     *
     * @param array $runnerArgs list of arguments as obtained from _parseArgs()
     * @param array $options list of options as constructed by _runnerOptions()
     * @return void
     */
    protected function _run($runnerArgs, $options = array()) {
        restore_error_handler();
        restore_error_handler();

        $testCli = new JetbrainsTestSuiteCommand('JetbrainsTestLoader', $runnerArgs);
        $testCli->run($options);
    }

    /**
     * Parse shell argument passed from IDE
     * and make it compatible with CakePHP
     *
     * @param   array  $argv
     * @return  array
     */
    protected function parseArgsIDE($argv) {
        $args = array();
        foreach ($argv as $key => $value) {
            if (strpos($value, 'phpunit.php') !== false) {
                // parse out path to IDE helpers lib
                $lib_path = $value;
            } elseif ($value == '-dxdebug.coverage_enable=1') {
                //TODO: this value crashing Cake, need to investigate
                continue;
            } else {
                $args[] = $value;
            }
        }
        // test-suffix flag is not supported
        // add the suffix to the test directory (passed as a last argument)
        // and remove the flag along with its value
        $suffixKey = array_search('--test-suffix', $args, true);
        if ($suffixKey) {
            $testPath = array_pop($args);
            $args[] = $testPath . DS . $args[$suffixKey + 1];
            unset($args[$suffixKey]);
            unset($args[$suffixKey + 1]);
        }

        if (!empty($lib_path)) {
            $this->includeLibIDE($lib_path);
        } else {
            throw new InvalidArgumentException('Missing path to IDE library!');
        }

        return $args;
    }

    /**
     * Include IDE helper library after disabling default method call
     *
     * @param  string  $path  Path to IDE helper library
     */
    protected function includeLibIDE($path) {
        $file_content = file_get_contents($path);

        if (strpos($file_content, '//IDE_PHPUnit_TextUI_Command::main') === false) {
            $file_content = str_replace('IDE_PHPUnit_TextUI_Command::main()', '//IDE_PHPUnit_TextUI_Command::main()', $file_content);
            file_put_contents($path, $file_content);
        }

        App::import('file', 'jb-phpunit', false, array(dirname($path) . DS), basename($path));
    }
}
