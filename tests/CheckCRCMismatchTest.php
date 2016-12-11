<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use RipLogChecker\Parsers\EacParser;
use RipLogChecker\RipLogChecker;

class CheckCRCMismatchTest extends TestCase
{
    public function testCRCMismatchCheck()
    {
        /* Load log file */
        $testLog = file_get_contents('tests/logs/crc_mismatch_test.log');

        /* Construct RipLogChecker object */
        $log_checker = new RipLogChecker($testLog);

        /* Retrieve the errors array */
        $errors = $log_checker->getParser()->getErrors();

        /* Assert that we get the C2_POINTERS_USED error */
        $this->assertEquals($errors[EacParser::CRC_MISMATCH], true);

        /* Verify that the score is equals the score that a log with only this error would have */
        $this->assertEquals(70, $log_checker->getScore());
    }
}
