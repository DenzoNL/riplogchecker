<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use RipLogChecker\RipLogChecker;
use RipLogChecker\Scorers\EacScorer;

class ID3TagsAddedTest extends TestCase
{
    public function testID3TagsAdded()
    {
        /* Load log file */
        $testLog = file_get_contents('tests/logs/id3_tags_test.log');

        /* Construct RipLogChecker object */
        $log_checker = new RipLogChecker($testLog);

        /* Retrieve the errors array */
        $errors = $log_checker->getScorer()->getErrors();

        /* Assert that we get the ID3_TAGS_ADDED error */
        $this->assertEquals($errors[EacScorer::ID3_TAGS_ADDED], true);

        /* Verify that the score is equals the score that a log with only this error would have */
        $this->assertEquals(100, $log_checker->getScore());
    }
}
