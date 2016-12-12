<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use RipLogChecker\Scorers\EacScorer;
use RipLogChecker\RipLogChecker;

class InsecureReadModeTest extends TestCase
{
    public function testDetectInsecureReadMode()
    {
        /* Load log file */
        $testLog = file_get_contents('tests/logs/insecure_read_mode_test.log');

        /* Construct RipLogChecker object */
        $log_checker = new RipLogChecker($testLog);

        /* Retrieve the errors array */
        $errors = $log_checker->getScorer()->getErrors();

        /* Assert that we get the INSECURE_MODE_USED error */
        $this->assertEquals($errors[EacScorer::INSECURE_MODE_USED], true);

        /* Verify that the score is equals the score that a log with only this error would have */
        $this->assertEquals(98, $log_checker->getScore());
    }
}
