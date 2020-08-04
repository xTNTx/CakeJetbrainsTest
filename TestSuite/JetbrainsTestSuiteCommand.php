<?php
App::uses('CakeTestSuiteCommand', 'TestSuite');

/**
 * Class JetbrainsTestSuiteCommand
 * Wrapper to adopt CakePHP test suit to JetBrains IDEs
 */
class JetbrainsTestSuiteCommand extends CakeTestSuiteCommand {

    /**
     * Setup JetBrains printer for correct parsing tests output
     *
     * @param array $argv
     */
    protected function handleArguments(array $argv) {
        parent::handleArguments($argv);

        if (isset($this->arguments['printer'])) {
            $printer = $this->arguments['printer'];
        } else {
            $printer = null;
        }

        $out = isset($this->arguments['stderr']) ? 'php://stderr' : null;
        $printer = new IDE_PHPUnit_TextUI_ResultPrinter($printer, $out);
        $this->arguments['printer'] = $printer;
        $this->arguments['listeners'][] = new IDE_PHPUnit_Framework_TestListener($printer);
    }
}
