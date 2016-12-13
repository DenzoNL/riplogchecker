<?php

namespace RipLogChecker\Parsers;

class EacParser extends BaseParser
{
    /**
     * Parse the contents of the log file to JSON.
     * Return true on success and false on failure.
     *
     * @param $log
     *
     * @return bool
     */
    public function parse($log): bool
    {
        $this->json = json_encode([
            'metadata' => [
                'software_version' => $this->getSoftwareVersion(),
                'log_date' => $this->getLogDate()
            ]
        ], JSON_PRETTY_PRINT);

        return true;
    }

    /**
     * Find the version of the software that was used.
     *
     * @return string
     */
    protected function getSoftwareVersion(): string
    {
        /* Search for a text line with the EAC version */
        $pos = strpos($this->log, 'Exact Audio Copy V');

        /* If we can't find it, return an error */
        if($pos === FALSE)
        {
            return 'Software version could not be determined.';
        }
        /* Return the full line of text starting at the position until end of line */
        else {
            return $this->readFromPosToEOL($pos);
        }
    }

    /**
     * Find the date on which the log was created.
     *
     * @return string
     */
    protected function getLogDate(): string
    {
        /* Search for the text that prepends the log date */
        $needle = 'EAC extraction logfile from ';

        /* Determine the start position of the timestamp */
        $pos = strpos($this->log, $needle) + strlen($needle);

        /* If nothing could be found, return this*/
        if($pos === FALSE)
        {
            return 'Log date could not be determined.';
        }
        else {
            /* If we found the timestamp, read from the start of the timestamp until EOL */
            return $this->readFromPosToEOL($pos);
        }
    }

    /**
     * Find the album artist.
     *
     * @return string
     */
    protected function getAlbumArtist(): string
    {
        // TODO: Implement getAlbumArtist() method.
    }

    /**
     * Find the album name.
     *
     * @return string
     */
    protected function getAlbumName(): string
    {
        // TODO: Implement getAlbumName() method.
    }

    /**
     * Find the drive that was used to rip the CD.
     *
     * @return string
     */
    protected function getDriveName(): string
    {
        // TODO: Implement getDriveName() method.
    }

    /**
     * Find the checksum of the CD rip.
     *
     * @return string
     */
    protected function getChecksum(): string
    {
        // TODO: Implement getChecksum() method.
    }

    /**
     * Find the read mode that was used.
     *
     * @return string
     */
    protected function getReadMode(): string
    {
        // TODO: Implement getReadMode() method.
    }

    /**
     * Find whether accurate stream was utilized.
     *
     * @return bool
     */
    protected function getAccurateStream(): bool
    {
        // TODO: Implement getAccurateStream() method.
    }

    /**
     * Find whether defeat audio cache is enabled.
     *
     * @return bool
     */
    protected function getDefeatAudioCache(): bool
    {
        // TODO: Implement getDefeatAudioCache() method.
    }

    /**
     * Find whether C2 Pointers are used.
     *
     * @return bool
     */
    protected function getC2Pointers(): bool
    {
        // TODO: Implement getC2Pointers() method.
    }

    /**
     * Find the read offset correction.
     *
     * @return int
     */
    protected function getReadOffsetCorrection(): int
    {
        // TODO: Implement getReadOffsetCorrection() method.
    }

    /**
     * Find whether overread into lead-in and lead-out
     * is enabled.
     *
     * @return bool
     */
    protected function getOverreadIntoLeadinLeadOut(): bool
    {
        // TODO: Implement getOverreadIntoLeadinLeadOut() method.
    }

    /**
     * Find whether "Fill up missing offset samples with silence"
     * is used.
     *
     * @return bool
     */
    protected function getFillUpOffsetSamples(): bool
    {
        // TODO: Implement getFillUpOffsetSamples() method.
    }

    /**
     * Find out whether null samples are used in CRC calculations.
     *
     * @return bool
     */
    protected function getNullSamplesUsed(): bool
    {
        // TODO: Implement getNullSamplesUsed() method.
    }

    /**
     * Get the interface that was used.
     *
     * @return string
     */
    protected function getUsedInterface(): string
    {
        // TODO: Implement getUsedInterface() method.
    }

    /**
     * Get the output format that was used.
     *
     * @return string
     */
    protected function getUsedOutputFormat(): string
    {
        // TODO: Implement getUsedOutputFormat() method.
    }

    /**
     * Find the bitrate in kilobits.
     *
     * @return int
     */
    protected function getSelectedBitrate(): int
    {
        // TODO: Implement getSelectedBitrate() method.
    }

    /**
     * Find the output quality that was used.
     *
     * @return string
     */
    protected function getOutputQuality(): string
    {
        // TODO: Implement getOutputQuality() method.
    }

    /**
     * Find whether ID3 tags were added or not.
     *
     * @return bool
     */
    protected function getID3TagsAdded(): bool
    {
        // TODO: Implement getID3TagsAdded() method.
    }

    /**
     * Get the executable that was used to compress the
     * .wav files.
     *
     * @return string
     */
    protected function getCompressorExecutable(): string
    {
        // TODO: Implement getCompressorExecutable() method.
    }

    /**
     * Find track data by track number.
     *
     * @param int $number
     *
     * @return string
     */
    protected function getTrackData(int $number): string
    {
        // TODO: Implement getTrackData() method.
    }

    /**
     * Get the filename of a given track.
     *
     * @param string $track
     *
     * @return string
     */
    protected function getTrackFileName(string $track): string
    {
        // TODO: Implement getTrackFileName() method.
    }

    /**
     * Get the pregap length for a given track.
     *
     * @param string $track
     *
     * @return string
     */
    protected function getTrackPregapLength(string $track): string
    {
        // TODO: Implement getTrackPregapLength() method.
    }

    /**
     * Gets the peak level for a given track.
     *
     * @param string $track
     *
     * @return float
     */
    protected function getTrackPeakLevel(string $track): float
    {
        // TODO: Implement getTrackPeakLevel() method.
    }

    /**
     * Gets the extraction speed for a given track.
     *
     * @param string $track
     *
     * @return float
     */
    protected function getTrackExtractionSpeed(string $track): float
    {
        // TODO: Implement getTrackExtractionSpeed() method.
    }

    /**
     * Gets the track quality for a given track.
     *
     * @param string $track
     *
     * @return float
     */
    protected function getTrackQuality(string $track): float
    {
        // TODO: Implement getTrackQuality() method.
    }

    /**
     * Gets the test CRC for a given track.
     *
     * @param string $track
     *
     * @return string
     */
    protected function getTrackTestCrc(string $track): string
    {
        // TODO: Implement getTrackTestCrc() method.
    }

    /**
     * Gets the copy CRC for a given track.
     *
     * @param string $track
     *
     * @return string
     */
    protected function getTrackCopyCrc(string $track): string
    {
        // TODO: Implement getTrackCopyCrc() method.
    }

    /**
     * Gets the AccurateRip confidence level for a given track.
     *
     * @param string $track
     *
     * @return int
     */
    protected function getAccurateRipConfidence(string $track): int
    {
        // TODO: Implement getAccurateRipConfidence() method.
    }

    /**
     * Get the copy result for a given track.
     *
     * @param string $track
     *
     * @return string
     */
    protected function getCopyResult(string $track): string
    {
        // TODO: Implement getCopyResult() method.
    }

    /**
     * Read from a given position until we hit an end of line character.
     *
     * @param $pos
     * @return string
     */
    protected function readFromPosToEOL($pos): string
    {
        return substr($this->log, $pos, strpos($this->log, PHP_EOL, $pos) - $pos);
    }
}