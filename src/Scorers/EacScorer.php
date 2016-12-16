<?php

namespace RipLogChecker\Scorers;

use RipLogChecker\Parsers\EacParser;

class EacScorer extends BaseScorer
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
        $this->parser = new EacParser($log);
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
    public function score(): bool
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
        $assertion = 'Secure';
        $result = $this->parser->data['options']['drive_options']['read_mode'];
        $check = self::INSECURE_MODE_USED;

        return $this->scoreResult($result, $assertion, $check);
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
        $assertion = true;
        $result = $this->parser->data['options']['drive_options']['defeat_audio_cache'];
        $check = self::DEFEAT_AUDIO_CACHE_DISABLED;

        return $this->scoreResult($result, $assertion, $check);
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
        $assertion = false;
        $result = $this->parser->data['options']['drive_options']['make_use_of_c2_pointers'];
        $check = self::C2_POINTERS_USED;

        return $this->scoreResult($result, $assertion, $check);
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
        $assertion = true;
        $result = $this->parser->data['options']['read_options']['fill_up_missing_offset_samples_with_silence'];
        $check = self::DOES_NOT_FILL_MISSING_SAMPLES;

        return $this->scoreResult($result, $assertion, $check);
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
        $assertion = false;
        $result = $this->parser->data['options']['read_options']['delete_leading_trailing_silent_blocks'];
        $check = self::DELETES_SILENT_BLOCKS;

        return $this->scoreResult($result, $assertion, $check);
    }

    /**
     * Check if Null samples are used in CRC calculations,
     * set the $deductedPoints and return false if it fails.
     *
     * @return bool
     */
    protected function checkNullSamplesUsed(): bool
    {
        $assertion = true;
        $result = $this->parser->data['options']['read_options']['null_samples_used_in_crc_calculations'];
        $check = self::NULL_SAMPLES_NOT_USED;

        return $this->scoreResult($result, $assertion, $check);
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
        $assertion = 'Appended to previous track';
        $result = $this->parser->data['options']['read_options']['gap_handling'];
        $check = self::GAP_HANDLING_NOT_DETECTED;

        return $this->scoreResult($result, $assertion, $check);
    }

    /**
     * Check whether ID3 tags were added. Set
     * $deductedPoints and return false on failure.
     *
     * @return bool
     */
    protected function checkID3TagsAdded(): bool
    {
        $assertion = false;
        $result = $this->parser->data['options']['output_options']['add_id3_tag'];
        $check = self::ID3_TAGS_ADDED;

        return $this->scoreResult($result, $assertion, $check);
    }

    /**
     * Check if there are CRC mismatches in the log.
     * Set $deductedPoints and return false on failure.
     *
     * @return bool
     */
    protected function checkCRCMismatch(): bool
    {
        /* Initialize arrays */
        $testCRCs = [];
        $copyCRCs = [];

        /* Save Test CRCs into array */
        foreach ($this->parser->data['tracks'] as $match) {
            $testCrc = $match['checksums']['test_crc'];
            array_push($testCRCs, $testCrc);
            $copyCrc = $match['checksums']['copy_crc'];
            array_push($copyCRCs, $copyCrc);
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
        foreach ($this->parser->data['tracks'] as $track) {
            if ($track['copy'] != 'OK') {
                $this->errors[self::TEST_COPY_NOT_USED] = true;
                $this->deductedPoints += parent::$pointDeductions[self::TEST_COPY_NOT_USED];
            }
        }

        if (!isset($this->errors[self::TEST_COPY_NOT_USED])) {
            $this->errors[self::TEST_COPY_NOT_USED] = false;
        }

        return true;
    }

    /**
     * Check whether result equals the given assertion,
     * and if not deduct points according to the given
     * check.
     *
     * @param $result
     * @param $assertion
     * @param $check
     *
     * @return bool
     */
    protected function scoreResult($result, $assertion, $check): bool
    {
        if ($result == $assertion) {
            $this->errors[$check] = false;

            return true;
        } else {
            $this->errors[$check] = true;
            $this->deductedPoints += self::$pointDeductions[$check];

            return true;
        }
    }
}
