<?php
namespace Tests;

use elc\elc;
use PHPUnit\Framework\TestCase;

class LoadLogFileTest extends TestCase
{
    public function testCanLoadLogFile()
    {
        // Load log file
        $testLog = file_get_contents('tests/test.log');

        // Construct elc object
        $log_checker = new elc($testLog);

        // Assert that the log file is not empty
        $this->assertNotEmpty($log_checker->getLog());
    }
}