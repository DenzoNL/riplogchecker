<?php

namespace RipLogChecker\Parsers;

abstract class BaseParser
{
    /**
     * The raw contents of the log.
     *
     * @var string;
     */
    protected $log;
    /**
     * The contents of the log converted to JSON.
     *
     * @var string
     */
    protected $json;

    /**
     * BaseParser constructor. Initializes the
     * class with the log file.
     *
     * @param $log
     */
    public function __construct($log)
    {
        $this->log = $log;
        $this->parse($log);
    }

    /**
     * Return the contents of the log in JSON format.
     *
     * @return string
     */
    public function getJson(): string
    {
        return $this->json;
    }

    /**
     * Parse the contents of the log file to JSON.
     * Return true on success and false on failure.
     *
     * @param $log
     *
     * @return bool
     */
    abstract public function parse($log): bool;

    /**
     * Find the version of the software that was used.
     *
     * @return string
     */
    abstract protected function getSoftwareVersion(): string;

    /**
     * Find the date on which the log was created.
     *
     * @return string
     */
    abstract protected function getLogDate(): string;

    /**
     * Find the album artist.
     *
     * @return string
     */
    abstract protected function getAlbumArtist(): string;

    /**
     * Find the album name.
     *
     * @return string
     */
    abstract protected function getAlbumName(): string;

    /**
     * Find the drive that was used to rip the CD.
     *
     * @return string
     */
    abstract protected function getDriveName(): string;

    /**
     * Find the checksum of the CD rip.
     *
     * @return string
     */
    abstract protected function getChecksum(): string;

    /**
     * Find whether the log contains the text 'All tracks accurately ripped'.
     *
     * @return bool
     */
    abstract protected function getAllTracksAccuratelyRipped(): bool;

    /**
     * Find out whether the log contains the text 'No errors occurred'.
     *
     * @return bool
     */
    abstract protected function getNoErrorsOccurred(): bool;
    /**
     * Find the read mode that was used.
     *
     * @return string
     */
    abstract protected function getReadMode(): string;

    /**
     * Find whether accurate stream was utilized.
     *
     * @return bool
     */
    abstract protected function getAccurateStream(): bool;

    /**
     * Find whether defeat audio cache is enabled.
     *
     * @return bool
     */
    abstract protected function getDefeatAudioCache(): bool;

    /**
     * Find whether C2 Pointers are used.
     *
     * @return bool
     */
    abstract protected function getC2Pointers(): bool;

    /**
     * Find the read offset correction.
     *
     * @return int
     */
    abstract protected function getReadOffsetCorrection(): int;

    /**
     * Find whether overread into lead-in and lead-out
     * is enabled.
     *
     * @return bool
     */
    abstract protected function getOverreadIntoLeadinLeadOut(): bool;

    /**
     * Find whether "Fill up missing offset samples with silence"
     * is used.
     *
     * @return bool
     */
    abstract protected function getFillUpOffsetSamples(): bool;

    /**
     * Find out whether leading and trailing silent blocks
     * are deleted.
     *
     * @return bool
     */
    abstract protected function getDeleteSilentBlocks(): bool;
    /**
     * Find out whether null samples are used in CRC calculations.
     *
     * @return bool
     */
    abstract protected function getNullSamplesUsed(): bool;

    /**
     * Get the interface that was used.
     *
     * @return string
     */
    abstract protected function getUsedInterface(): string;

    /**
     * Get the gap handling mode that was used.
     *
     * @return string
     */
    abstract protected function getGapHandling(): string;
    /**
     * Get the output format that was used.
     *
     * @return string
     */
    abstract protected function getUsedOutputFormat(): string;

    /**
     * Find the bitrate in kilobits.
     *
     * @return int
     */
    abstract protected function getSelectedBitrate(): int;

    /**
     * Find the output quality that was used.
     *
     * @return string
     */
    abstract protected function getOutputQuality(): string;

    /**
     * Find whether ID3 tags were added or not.
     *
     * @return bool
     */
    abstract protected function getID3TagsAdded(): bool;

    /**
     * Get the executable that was used to compress the
     * .wav files.
     *
     * @return string
     */
    abstract protected function getCompressorExecutable(): string;

    /**
     * Find track data by track number.
     *
     *
     * @return array
     */
    abstract protected function getTrackData(): array;

    /**
     * Get the filename of a given track.
     *
     * @param int $pos
     * @return string
     */
    abstract protected function getTrackFileName(int $pos): string;

    /**
     * Get the pregap length for a given track.
     *
     * @param int $pos
     * @return string
     *
     */
    abstract protected function getTrackPregapLength(int $pos): string;

    /**
     * Gets the peak level for a given track.
     *
     * @param int $pos
     * @return float
     *
     */
    abstract protected function getTrackPeakLevel(int $pos): float;

    /**
     * Gets the extraction speed for a given track.
     *
     * @param int $pos
     * @return float
     *
     */
    abstract protected function getTrackExtractionSpeed(int $pos): float;

    /**
     * Gets the track quality for a given track.
     *
     * @param int $pos
     * @return float
     *
     */
    abstract protected function getTrackQuality(int $pos): float;

    /**
     * Gets the test CRC for a given track.
     *
     * @param int $pos
     * @return string
     *
     */
    abstract protected function getTrackTestCrc(int $pos): string;

    /**
     * Gets the copy CRC for a given track.
     *
     * @param int $pos
     * @return string
     *
     */
    abstract protected function getTrackCopyCrc(int $pos): string;

    /**
     * Gets the AccurateRip confidence level for a given track.
     *
     * @param int $pos
     * @return int
     *
     */
    abstract protected function getTrackAccurateRipConfidence(int $pos): int;

    /**
     * Get the copy result for a given track.
     *
     * @param int $pos
     * @return string
     *
     */
    abstract protected function getTrackCopyResult(int $pos): string;
}
