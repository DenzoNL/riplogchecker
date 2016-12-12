<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use RipLogChecker\Scorers\EacScorer;
use RipLogChecker\RipLogChecker;

class OffsetSampleFillUsedTest extends TestCase
{
    public function testOffsetSampleFillCheck()
    {
        /* Load log file */
        $testLog = file_get_contents('tests/logs/offset_sample_fill_disabled.log');

        /* Construct RipLogChecker object */
        $log_checker = new RipLogChecker($testLog);

        /* Retrieve the errors array */
        $errors = $log_checker->getScorer()->getErrors();

        /* Assert that we get the DOES_NOT_FILL_MISSING_SAMPLES error */
        $this->assertEquals($errors[EacScorer::DOES_NOT_FILL_MISSING_SAMPLES], true);

        /* Verify that the score is equals the score that a log with only this error would have */
        $this->assertEquals(95, $log_checker->getScore());
    }
}
