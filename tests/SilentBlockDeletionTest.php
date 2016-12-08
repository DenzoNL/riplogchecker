<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use RipLogChecker\Parsers\EacParser;
use RipLogChecker\RipLogChecker;

class SilentBlockDeletionTest extends TestCase
{
    public function testSilentBlockDeletionUsedCheck()
    {
        /* Load log file */
        $testLog = file_get_contents('tests/logs/silent_block_deletion_used_test.log');

        /* Construct RipLogChecker object */
        $log_checker = new RipLogChecker($testLog);

        /* Retrieve the errors array */
        $errors = $log_checker->getParser()->getErrors();

        /* Assert that we get the DELETES_SILENT_BLOCKS error */
        $this->assertEquals($errors[EacParser::DELETES_SILENT_BLOCKS], true);

        /* Verify that the score is equals the score that a log with only this error would have */
        $this->assertEquals(95, $log_checker->getScore());
    }
}