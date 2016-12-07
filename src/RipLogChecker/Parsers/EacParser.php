<?php

namespace RipLogChecker\Parsers;

class EacParser extends BaseParser
{
    /**
     * Creates a new EacParser object based on the log file provided, and parses it
     *
     * RipLogChecker constructor.
     * @param string $log
     */
    public function __construct(string $log)
    {
        $this->log = $log;
        $this->parse();
    }

    public function parse(): bool
    {
        /* If the log is empty, return false */
        if(!$this->log)
        {
            return false;
        }

        /* Parse $this->log */
        /* TODO: log score deductions */

        /* Set deducted points */
        $deductedPoints = 0;
        $this->setDeductedPoints($deductedPoints);

        return true;
    }
}