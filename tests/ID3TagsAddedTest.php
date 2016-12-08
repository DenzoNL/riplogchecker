<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use RipLogChecker\Parsers\EacParser;
use RipLogChecker\RipLogChecker;

class ID3TagsAddedTest extends TestCase
{
    public function testC2PointersUsedCheck()
    {
        /* Load log file */
        $testLog = file_get_contents('tests/logs/id3_tags_test.log');

        /* Construct RipLogChecker object */
        $log_checker = new RipLogChecker($testLog);

        /* Retrieve the errors array */
        $errors = $log_checker->getParser()->getErrors();

        /* Assert that we get the C2_POINTERS_USED error */
        $this->assertEquals($errors[EacParser::ID3_TAGS_ADDED], true);

        /* Verify that the score is equals the score that a log with only this error would have */
        $this->assertEquals(100, $log_checker->getScore());
    }
}