<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use RipLogChecker\Scorers\EacScorer;
use RipLogChecker\RipLogChecker;

class GapHandlingTest extends TestCase
{
    public function testGapHandlingCheck()
    {
        /* Load log file */
        $testLog = file_get_contents('tests/logs/gap_handling_test.log');

        /* Construct RipLogChecker object */
        $log_checker = new RipLogChecker($testLog);

        /* Retrieve the errors array */
        $errors = $log_checker->getScorer()->getErrors();

        /* Assert that we get the GAP_HANDLING_NOT_DETECTED error */
        $this->assertEquals($errors[EacScorer::GAP_HANDLING_NOT_DETECTED], true);

        /* Verify that the score is equals the score that a log with only this error would have */
        $this->assertEquals(95, $log_checker->getScore());
    }
}
