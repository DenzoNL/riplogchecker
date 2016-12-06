<?php

namespace elc;

class elc
{
    /**
     * The full text of the log file
     *
     * @var string
     */
    protected $log;

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
     * Analyze the log and return a score
     *
     * @return int
     */
    protected function scoreLog(): int
    {
        /* Create parser object and pass the log */
        $parser = new parser($this->log);

        /* Generate the final score */
        $score = 100 - $parser->getDeductedPoints();

        /* Finally, return the score */
        return $score;
    }

    /**
     * Creates a new elc object based on the log file, and scores it
     *
     * elc constructor.
     * @param string $log
     */
    public function __construct(string $log)
    {
        $this->setLog($log);
        $this->score = $this->scoreLog();
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
}