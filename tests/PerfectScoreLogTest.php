<?php
namespace Tests;

use elc\elc;
use PHPUnit\Framework\TestCase;

class PerfectScoreLogTest extends TestCase
{
    public function testLogHasPerfectScore()
    {
        // Load log file
        $testLog = file_get_contents('tests/test.log');

        // Construct elc object
        $log_checker = new elc($testLog);

        // Assert that the log score equals 100
        $this->assertEquals(100, $log_checker->getScore());
    }
}