<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use RipLogChecker\RipLogChecker;

class PerfectScoreLogTest extends TestCase
{
    public function testLogHasPerfectScore()
    {
        /* Load log file */
        $testLog = file_get_contents('tests/logs/perfect_log_test.log');

        /* Construct RipLogChecker object*/
        $log_checker = new RipLogChecker($testLog);

        /* Assert that the log score equals a perfect 100 score*/
        $this->assertEquals(100, $log_checker->getScore());
    }
}
