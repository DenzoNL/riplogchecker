<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use RipLogChecker\Parsers\EacParser;
use RipLogChecker\RipLogChecker;

class DefeatAudioCacheTest extends TestCase
{
    public function testDefeatAudioCacheCheck()
    {
        /* Load log file */
        $testLog = file_get_contents('tests/logs/defeat_audio_cache_test.log');

        /* Construct RipLogChecker object */
        $log_checker = new RipLogChecker($testLog);

        /* Retrieve the errors array */
        $errors = $log_checker->getParser()->getErrors();

        /* Assert that we get the DEFEAT_AUDIO_CACHE_DISABLED error */
        $this->assertEquals($errors[EacParser::DEFEAT_AUDIO_CACHE_DISABLED], true);

        /* Verify that the score is equals the score that a log with only this error would have */
        $this->assertEquals(95, $log_checker->getScore());
    }
}
