<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use RipLogChecker\RipLogChecker;
use RipLogChecker\Scorers\EacScorer;

class CheckCRCMismatchTest extends TestCase
{
    public function testCRCMismatchCheck()
    {
        /* Load log file */
        $testLog = file_get_contents('tests/logs/crc_mismatch_test.log');

        /* Construct RipLogChecker object */
        $log_checker = new RipLogChecker($testLog);

        /* Retrieve the errors array */
        $errors = $log_checker->getScorer()->getErrors();

        /* Assert that we get the CRC MISMATCH error */
        $this->assertEquals($errors[EacScorer::CRC_MISMATCH], true);

        /* Verify that the score is equals the score that a log with only this error would have */
        $this->assertEquals(70, $log_checker->getScore());
    }
}
