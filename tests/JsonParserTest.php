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

        $data = $logChecker->getScorer()->getParser()->data;
        unset($data['riplogchecker_version']);
        $parsedJson = json_encode($data, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION);

        /* Assert that the test JSON equals the parsed JSON */
        $this->assertJsonStringEqualsJsonString($testJson, $parsedJson);
    }
}
