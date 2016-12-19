<?php

namespace RipLogChecker;

use RipLogChecker\Scorers\BaseScorer;
use RipLogChecker\Scorers\EacScorer;

class RipLogChecker
{
    /**
     * The full text of the log file.
     *
     * @var string
     */
    protected $log;

    /**
     * @var BaseScorer
     */
    protected $scorer;

    /**
     * The log score.
     *
     * @var int
     */
    protected $score;

    /**
     * Gets log file.
     *
     * @return string
     */
    public function getLog(): string
    {
        return $this->log;
    }

    /**
     * Sets the log file.
     *
     * @param string $log
     */
    protected function setLog($log)
    {
        /* Normalize the line endings */
        /* Credits: https://www.darklaunch.com/2009/05/06/php-normalize-newlines-line-endings-crlf-cr-lf-unix-windows-mac */
        $log = str_replace("\r\n", "\n", $log);
        $log = str_replace("\r", "\n", $log);
        // Don't allow out-of-control blank lines
        $log = preg_replace("/\n{2,}/", "\n\n", $log);

        $this->log = $log;
    }

    /**
     * Analyze the log set the score variable.
     *
     * @return bool
     */
    protected function scoreLog(): bool
    {
        /* Create Parser object and pass the log */
        $this->scorer = new EacScorer($this->log);

        /* Parse the log */
        if ($this->scorer->score()) {
            $this->score += $this->scorer->getDeductedPoints();

            return true;
        } else {
            $this->score = 0;

            return false;
        }
    }

    /**
     * Creates a new RipLogChecker object based on the log file, and scores it.
     *
     * RipLogChecker constructor.
     *
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
     * Get the log score.
     *
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @return BaseScorer
     */
    public function getScorer(): BaseScorer
    {
        return $this->scorer;
    }

    /**
     * @param BaseScorer $scorer
     */
    public function setScorer(BaseScorer $scorer)
    {
        $this->scorer = $scorer;
    }

    /**
     * Returns the version string
     *
     * @return string
     */
    public static function getVersion()
    {
        return file_get_contents(__DIR__.'/../'.'VERSION');
    }
}
