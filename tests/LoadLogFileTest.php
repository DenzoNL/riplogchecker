<?php
namespace Tests;

use RipLogChecker\RipLogChecker;
use PHPUnit\Framework\TestCase;

class LoadLogFileTest extends TestCase
{
    public function testCanLoadLogFile()
    {
        /* Load log file */
        $testLog = file_get_contents('tests/logs/perfect_log_test.log');

        /* Construct RipLogChecker object */
        $log_checker = new RipLogChecker($testLog);

        /* Assert that the log checker log file equals the one we loaded */
        $this->assertEquals($testLog, $log_checker->getLog());
    }
}