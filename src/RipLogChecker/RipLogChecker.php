<?php

namespace RipLogChecker;

use RipLogChecker\Parsers\BaseParser;
use RipLogChecker\Parsers\EacParser;

class RipLogChecker
{
    /**
     * The full text of the log file
     *
     * @var string
     */
    protected $log;

    /**
     * @var BaseParser
     */
    protected $parser;

    /**
     * The log score
     *
     * @var int
     */
    protected $score;

    /**
     * Gets log file
     *
     * @return string
     */
    public function getLog(): string
    {
        return $this->log;
    }

    /**
     * Sets the log file
     *
     * @param string $log
     */
    protected function setLog($log)
    {
        $this->log = $log;
    }

    /**
     * Analyze the log set the score variable
     *
     * @return bool
     */
    protected function scoreLog(): bool
    {
        /* Create Parser object and pass the log */
        $this->parser = new EacParser($this->log);

        /* Parse the log */
        if ($this->parser->parse()) {
            $this->score += $this->parser->getDeductedPoints();
            return true;
        } else {
            $this->score = 0;
            return false;
        }

    }

    /**
     * Creates a new RipLogChecker object based on the log file, and scores it
     *
     * RipLogChecker constructor.
     * @param string $log
     */
    public function __construct(string $log)
    {
        /* Load the log file */
        $this->setLog($log);

        /* Initialize the default score to 100*/
        $this->score = 100;

        /* Score the log */
        $this->scoreLog();
    }

    /**
     * Get the log score
     *
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @return BaseParser
     */
    public function getParser(): BaseParser
    {
        return $this->parser;
    }

    /**
     * @param BaseParser $parser
     */
    public function setParser(BaseParser $parser)
    {
        $this->parser = $parser;
    }
}