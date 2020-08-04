<?php
App::uses('CakeTestLoader', 'TestSuite');

/**
 * Class JetbrainsTestLoader
 * Wrapper to adopt CakePHP test suit to JetBrains IDEs
 */
class JetbrainsTestLoader extends CakeTestLoader {

    /**
     * Leave test file path without modification
     *
     * @param string $filePath
     * @param string $params
     * @return string
     */
    protected function _resolveTestFile($filePath, $params) {
        return parent::_resolveTestFile($filePath, $params);
    }
}
