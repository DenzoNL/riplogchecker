<?php

namespace RipLogChecker\Parsers;

abstract class BaseParser
{
    /**
     * The point deduction constants
     */
    const DEFEAT_AUDIO_CACHE_DISABLED = -5;
    const C2_POINTERS_USED = -10;
    const INSECURE_MODE_USED = -2;
    const DOES_NOT_FILL_MISSING_SAMPLES = -5;
    const GAP_HANDLING_NOT_DETECTED = -5;
    const DELETES_SILENT_BLOCKS = -5;
    const NULL_SAMPLES_NOT_USED = -5;
    const ID3_TAGS_ADDED = 0;
    const CRC_MISMATCH = -30;
    const TEST_COPY_NOT_USED = -10;

    /**
     * Explanatory messages for the deductions
     *
     * @var array
     */
    protected static $deductions = array(
        self::DEFEAT_AUDIO_CACHE_DISABLED => 'Defeat audio cache should be yes (-5 points)',
        self::C2_POINTERS_USED => 'C2 pointers were used (-10 points)',
        self::INSECURE_MODE_USED => 'Insecure mode was used (-2 points)',
        self::DOES_NOT_FILL_MISSING_SAMPLES => 'Does not fill up missing offset samples with silence (-5 points)',
        self::GAP_HANDLING_NOT_DETECTED => 'Gap handling was not detected (-5 points)',
        self::DELETES_SILENT_BLOCKS => 'Deletes leading and trailing silent blocks (-5 points)',
        self::NULL_SAMPLES_NOT_USED => 'Null samples should be used in CRC calculations (-5 points)',
        self::ID3_TAGS_ADDED => 'ID3 tags should not be added to FLAC files. FLAC files should have Vorbis comments for tags instead.',
        self::CRC_MISMATCH => 'CRC mismatch (-30 points)',
        self::TEST_COPY_NOT_USED => 'Test and Copy was not used (-10 points)',
    );

    /**
     * @var int
     */
    protected $deductedPoints;

    /**
     * The full text of the log file
     *
     * @var string
     */
    protected $log;

    /**
     * Parses the log file, and returns false if it
     * fails to parse the log file
     *
     * @return bool
     */
    abstract public function parse(): bool;

    /**
     * Get the number of points deducted
     *
     * @return int
     */
    public function getDeductedPoints(): int
    {
        return $this->deductedPoints;
    }

    /**
     * Sets the number of points deducted as a result of checking the logs
     *
     * @param int $deductedPoints
     */
    protected function setDeductedPoints($deductedPoints)
    {
        $this->deductedPoints = $deductedPoints;
    }

    /**
     * Check if the read mode set to secure,
     * set the $deductedPoints and return false if it fails
     * to check the read mode
     *
     * @return bool
     */
    abstract protected function checkReadMode(): bool;

    /**
     * Check if "Defeat audio cache" is set to yes,
     * set the $deductedPoints and return false if it fails
     * to check the read mode
     *
     * @return bool
     */
    abstract protected function checkDefeatAudioCache(): bool;

    /**
     * Check if C2 pointers is set to no,
     * set the $deductedPoints and return false if it fails
     * to check the read mode
     *
     * @return bool
     */
    abstract protected function checkC2PointersUsed(): bool;

    /**
     * Check whether "Fill up missing offset samples with silence"
     * is set to yes and set the $deductedPoints and return false
     * if it fails
     *
     * @return bool
     */
    abstract protected function checkFillUpOffsetSamples(): bool;

    /**
     * Check if "Delete leading and trailing silent blocks"
     * is set to "No" and set the $deductedPoints and return
     * false if it fails
     *
     * @return bool
     */
    abstract protected function checkSilentBlockDeletion(): bool;

    /**
     * Check if Null samples are used in CRC calculations,
     * set the $deductedPoints and return false if it fails
     *
     * @return bool
     */
    abstract protected function checkNullSamplesUsed(): bool;

    /**
     * Check whether gap handling was detected
     * and if the correct mode was used, then set
     * $deductedPoints. Returns false on failure;
     *
     * @return bool
     */
    abstract protected function checkGapHandling(): bool;

    /**
     * Check whether ID3 tags were added. Set
     * $deductedPoints and return false on failure.
     *
     * @return bool
     */
    abstract protected function checkID3TagsAdded(): bool;

    /**
     * Check if there are CRC mismatches in the log.
     * Set $deductedPoints and return false on failure.
     *
     * @return bool
     */
    abstract protected function checkCRCMismatch(): bool;

    /**
     * Check if Test & Copy is used, and deduct points if not.
     * Return false on failure.
     *
     * @return bool
     */
    abstract protected function checkTestCopyUsed(): bool;
}