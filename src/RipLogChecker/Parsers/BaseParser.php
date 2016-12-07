<?php

namespace RipLogChecker\Parsers;

class BaseParser
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
     * Creates a new Parser object based on the log file, and parses it
     *
     * RipLogChecker constructor.
     * @param string $log
     */
    public function __construct(string $log)
    {
        $this->log = $log;
        $this->parse();
    }

    /**
     *
     */
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

    /**
     * @return int
     */
    public function getDeductedPoints(): int
    {
        return $this->deductedPoints;
    }

    /**
     * @param int $deductedPoints
     */
    public function setDeductedPoints($deductedPoints)
    {
        $this->deductedPoints = $deductedPoints;
    }

}