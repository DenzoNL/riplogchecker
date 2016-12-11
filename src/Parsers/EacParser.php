<?php

namespace RipLogChecker\Parsers;

class EacParser extends BaseParser
{
    /**
     * Creates a new EacParser object based on the log file provided, and parses it.
     *
     * RipLogChecker constructor.
     *
     * @param string $log
     */
    public function __construct(string $log)
    {
        /* Load the log file */
        $this->log = $log;
        $this->errors = [];
        /* Initialize deducted points */
        $this->setDeductedPoints(0);
    }

    /**
     * Parses the log file, and returns false if it
     * fails to parse the log file.
     *
     * @return bool
     */
    public function parse(): bool
    {
        /* If the log is empty, return false */
        if (!$this->log) {
            return false;
        }

        /* Parse $this->log, and return false if even one check fails
         * TODO: refactor this into something nicer if possible
         */
        if (!$this->checkReadMode()) {
            return false;
        }
        if (!$this->checkDefeatAudioCache()) {
            return false;
        }
        if (!$this->checkC2PointersUsed()) {
            return false;
        }
        if (!$this->checkFillUpOffsetSamples()) {
            return false;
        }
        if (!$this->checkSilentBlockDeletion()) {
            return false;
        }
        if (!$this->checkNullSamplesUsed()) {
            return false;
        }
        if (!$this->checkGapHandling()) {
            return false;
        }
        if (!$this->checkID3TagsAdded()) {
            return false;
        }
        if (!$this->checkCRCMismatch()) {
            return false;
        }
        if (!$this->checkTestCopyUsed()) {
            return false;
        }

        /* Log parsed successfully, return true */
        return true;
    }

    /**
     * Check whether the read mode is secure or insecure,
     * set the $deductedPoints and return false if it fails
     * to check the read mode.
     *
     * @return bool
     */
    protected function checkReadMode(): bool
    {
        $pattern = '/Read mode               : Secure/';
        $result = preg_match($pattern, $this->log, $matches);

        return $this->processResult($result, self::INSECURE_MODE_USED);
    }

    /**
     * Check if "Defeat audio cache" is set to yes,
     * set the $deductedPoints and return false if it fails
     * to check the read mode.
     *
     * @return bool
     */
    protected function checkDefeatAudioCache(): bool
    {
        $pattern = '/Defeat audio cache      : Yes/';
        $result = preg_match($pattern, $this->log, $matches);

        return $this->processResult($result, self::DEFEAT_AUDIO_CACHE_DISABLED);
    }

    /**
     * Check if C2 pointers is set to no,
     * set the $deductedPoints and return false if it fails
     * to check the read mode.
     *
     * @return bool
     */
    protected function checkC2PointersUsed(): bool
    {
        $pattern = '/Make use of C2 pointers : No/';
        $result = preg_match($pattern, $this->log, $matches);

        return $this->processResult($result, self::C2_POINTERS_USED);
    }

    /**
     * Check whether "Fill up missing offset samples with silence"
     * is set to yes and set the $deductedPoints and return false
     * if it fails.
     *
     * @return bool
     */
    protected function checkFillUpOffsetSamples(): bool
    {
        $pattern = '/Fill up missing offset samples with silence : Yes/';
        $result = preg_match($pattern, $this->log, $matches);

        return $this->processResult($result, self::DOES_NOT_FILL_MISSING_SAMPLES);
    }

    /**
     * Check if "Delete leading and trailing silent blocks"
     * is set to "No" and set the $deductedPoints and return
     * false if it fails.
     *
     * @return bool
     */
    protected function checkSilentBlockDeletion(): bool
    {
        $pattern = '/Delete leading and trailing silent blocks   : No/';
        $result = preg_match($pattern, $this->log, $matches);

        return $this->processResult($result, self::DELETES_SILENT_BLOCKS);
    }

    /**
     * Check if Null samples are used in CRC calculations,
     * set the $deductedPoints and return false if it fails.
     *
     * @return bool
     */
    protected function checkNullSamplesUsed(): bool
    {
        $pattern = '/Null samples used in CRC calculations       : Yes/';
        $result = preg_match($pattern, $this->log, $matches);

        return $this->processResult($result, self::NULL_SAMPLES_NOT_USED);
    }

    /**
     * Check whether gap handling was detected
     * and if the correct mode was used, then set
     * $deductedPoints. Returns false on failure;.
     *
     * @return bool
     */
    protected function checkGapHandling(): bool
    {
        $pattern = '/Gap handling                                : Appended to previous track/';
        $result = preg_match($pattern, $this->log, $matches);

        return $this->processResult($result, self::GAP_HANDLING_NOT_DETECTED);
    }

    /**
     * Check whether ID3 tags were added. Set
     * $deductedPoints and return false on failure.
     *
     * @return bool
     */
    protected function checkID3TagsAdded(): bool
    {
        $pattern = '/Add ID3 tag                     : No/';
        $result = preg_match($pattern, $this->log, $matches);

        return $this->processResult($result, self::ID3_TAGS_ADDED);
    }

    /**
     * Check if there are CRC mismatches in the log.
     * Set $deductedPoints and return false on failure.
     *
     * @return bool
     */
    protected function checkCRCMismatch(): bool
    {
        /* Find all matches of Test CRC and Copy CRC */
        preg_match_all('/Test CRC/', $this->log, $test_matches, PREG_OFFSET_CAPTURE);
        preg_match_all('/Copy CRC/', $this->log, $copy_matches, PREG_OFFSET_CAPTURE);

        /* Initialize arrays */
        $testCRCs = [];
        $copyCRCs = [];

        /* Save Test CRCs into array */
        foreach ($test_matches[0] as $match) {
            $crc = substr($this->log, $match[1] + 9, 8);
            array_push($testCRCs, $crc);
        }

        /* Save Copy CRCs into array*/
        foreach ($copy_matches[0] as $match) {
            $crc = substr($this->log, $match[1] + 9, 8);
            array_push($copyCRCs, $crc);
        }

        /* Compare the arrays */

        $result = array_diff($testCRCs, $copyCRCs);

        /* If the array diff is empty, there are no mismatching CRCs*/
        /* TODO: Save a list of mismatching CRCs for error reporting */

        if ($result == null) {
            return true;
        } elseif ($result != null) {
            /* TODO: Report error for every CRC mismatch */
            $this->errors[self::CRC_MISMATCH] = true;
            /* TODO: Deduct points for every mismatch */
            $this->deductedPoints += parent::$pointDeductions[self::CRC_MISMATCH];

            return true;
        }

        /* Something went wrong! */
        return false;
    }

    /**
     * Check if Test & Copy is used, and deduct points if not.
     * Return false on failure.
     *
     * @return bool
     */
    protected function checkTestCopyUsed(): bool
    {
        // TODO: Implement checkTestCopyUsed() method.
        return true;
    }

    /**
     * Processes the result of a preg_match().
     *
     * @param $result - The preg_match() result
     * @param $check - The point deduction check constant
     *
     * @return bool
     */
    protected function processResult($result, $check): bool
    {
        /* If we found a match, return true */
        if ($result === 1) {
            /* Set INSECURE_MODE_USED to false in $this->errors */
            $this->errors[$check] = false;

            /* Return true */
            return true;
        } /* If we haven't found a match, deduct score and return true */
        elseif ($result === 0) {
            /* Add -2 points to $this->deductedPoints */
            $this->deductedPoints += parent::$pointDeductions[$check];

            /* Set INSECURE_MODE_USED to true in $this->errors */
            $this->errors[$check] = true;

            return true;
        }

        /* Return false if preg_match fails */
        return $result;
    }
}
