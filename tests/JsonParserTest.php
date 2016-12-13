<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use RipLogChecker\RipLogChecker;

class JsonParserTest extends TestCase
{
    public function testJsonParserOutputEqualsJsonTestFile()
    {
        /* Load log file */
        $testLog = file_get_contents('tests/logs/perfect_log_test.log');
        $testJson = file_get_contents('tests/logs/perfect_log.json');

        /* Construct RipLogChecker object*/
        $logChecker = new RipLogChecker($testLog);

        $parsedJson = $logChecker->getScorer()->getParser()->getJson();

        /* Assert that the test JSON equals the parsed JSON */
        $this->assertJsonStringEqualsJsonString($testJson, $parsedJson);
    }
}
