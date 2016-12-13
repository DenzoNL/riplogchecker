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
                'log_date' => $this->getLogDate(),
                'album_artist' => $this->getAlbumArtist(),
                'album_name' => $this->getAlbumName(),
                'used_drive' => $this->getDriveName(),
                'checksum' => $this->getChecksum(),
                'all_tracks_accurately_ripped' => $this->getAllTracksAccuratelyRipped(),
                'no_errors_occurred' => $this->getNoErrorsOccurred()
            ],
            'options' => [
                'drive_options' => [
                    'read_mode' => $this->getReadMode(),
                    'utilize_accurate_stream' => $this->getAccurateStream(),
                    'defeat_audio_cache' => $this->getDefeatAudioCache(),
                    'make_use_of_c2_pointers' => $this->getC2Pointers()
                ],
                'read_options' => [
                    'read_offset_correction' => $this->getReadOffsetCorrection(),
                    'overread_into_leadin_and_leadout' => $this->getOverreadIntoLeadinLeadOut(),
                    'fill_up_missing_offset_samples_with_silence' => $this->getFillUpOffsetSamples(),
                    'delete_leading_trailing_silent_blocks' => $this->getDeleteSilentBlocks(),
                    'null_samples_used_in_crc_calculations' => $this->getNullSamplesUsed(),
                    'used_interface' => $this->getUsedInterface(),
                    'gap_handling' => $this->getGapHandling()
                ],
                'output_options' => [
                    'used_output_format' => $this->getUsedOutputFormat(),
                    'selected_bitrate' => $this->getSelectedBitrate(),
                    'quality' => $this->getOutputQuality(),
                    'add_id3_tag' => $this->getID3TagsAdded(),
                    'command_line_compressor' => $this->getCompressorExecutable()
                ]
            ],
            'tracks' => $this->getTrackData()
        ], JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION);

        if ($this->json) {
            return true;
        } else {
            /* $this->json is null, something went wrong*/
            return false;
        }
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
        if ($pos === FALSE) {
            return 'Software version could not be determined.';
        } /* Return the full line of text starting at the position until end of line */
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
        if ($pos === FALSE) {
            return 'Log date could not be determined.';
        } else {
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
        return "Could not find album artist";
    }

    /**
     * Find the album name.
     *
     * @return string
     */
    protected function getAlbumName(): string
    {
        // TODO: Implement getAlbumName() method.
        return "Could not find album name";
    }

    /**
     * Find the drive that was used to rip the CD.
     *
     * @return string
     */
    protected function getDriveName(): string
    {
        /* Determine the string that we're looking for */
        $needle = 'Used drive';

        /* Determine the start position of the used drive text */
        $pos = strpos($this->log, $needle) + strlen($needle);

        if ($pos === FALSE) {
            return 'Used drive could not be determined.';
        } else {
            $text = $this->readFromPosToEOL($pos);

            return $this->getTextAfterColon($text);
        }
    }

    /**
     * Find the checksum of the CD rip.
     *
     * @return string
     */
    protected function getChecksum(): string
    {
        /* Find if there is a checksum */
        $needle = "Log checksum";

        /* Determine the starting position of the checksum */
        $pos = strpos($this->log, $needle) + strlen($needle);

        if ($pos === FALSE) {
            return "Could not find log checksum!";
        } /* Return the 64-character checksum */
        else {
            return substr($this->log, $pos + 1, 64);
        }
    }

    /**
     * Find whether the log contains the text 'All tracks accurately ripped'.
     *
     * @return bool
     */
    protected function getAllTracksAccuratelyRipped(): bool
    {
        $needle = 'All tracks accurately ripped';

        if (strpos($this->log, $needle) === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Find out whether the log contains the text 'No errors occurred'.
     *
     * @return bool
     */
    protected function getNoErrorsOccurred(): bool
    {
        $needle = 'No errors occurred';

        if (strpos($this->log, $needle) === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Find the read mode that was used.
     *
     * @return string
     */
    protected function getReadMode(): string
    {
        /* Find if there is a read mode */
        $option = "Read mode";
        return $this->getOptionString($option);
    }

    /**
     * Find whether accurate stream was utilized.
     *
     * @return bool
     */
    protected function getAccurateStream(): bool
    {
        $option = "Utilize accurate stream";
        return $this->getOptionBoolean($option);
    }

    /**
     * Find whether defeat audio cache is enabled.
     *
     * @return bool
     */
    protected function getDefeatAudioCache(): bool
    {
        $option = "Defeat audio cache";
        return $this->getOptionBoolean($option);
    }

    /**
     * Find whether C2 Pointers are used.
     *
     * @return bool
     */
    protected function getC2Pointers(): bool
    {
        $option = "Make use of C2 pointers";
        return $this->getOptionBoolean($option);
    }

    /**
     * Find the read offset correction.
     *
     * @return int
     */
    protected function getReadOffsetCorrection(): int
    {
        $option = "Read offset correction";
        return $this->getOptionString($option);
    }

    /**
     * Find whether overread into lead-in and lead-out
     * is enabled.
     *
     * @return bool
     */
    protected function getOverreadIntoLeadinLeadOut(): bool
    {
        $option = "Overread into Lead-In and Lead-Out";
        return $this->getOptionBoolean($option);
    }

    /**
     * Find whether "Fill up missing offset samples with silence"
     * is used.
     *
     * @return bool
     */
    protected function getFillUpOffsetSamples(): bool
    {
        $option = "Fill up missing offset samples with silence";
        return $this->getOptionBoolean($option);
    }

    /**
     * Find out whether leading and trailing silent blocks
     * are deleted.
     *
     * @return bool
     */
    protected function getDeleteSilentBlocks(): bool
    {
        $option = "Delete leading and trailing silent blocks";
        return $this->getOptionBoolean($option);
    }

    /**
     * Find out whether null samples are used in CRC calculations.
     *
     * @return bool
     */
    protected function getNullSamplesUsed(): bool
    {
        $option = "Null samples used in CRC calculations";
        return $this->getOptionBoolean($option);
    }

    /**
     * Get the interface that was used.
     *
     * @return string
     */
    protected function getUsedInterface(): string
    {
        $option = "Used interface";
        return $this->getOptionString($option);
    }

    /**
     * Get the gap handling mode that was used.
     *
     * @return string
     */
    protected function getGapHandling(): string
    {
        $option = "Gap handling";
        return $this->getOptionString($option);
    }

    /**
     * Get the output format that was used.
     *
     * @return string
     */
    protected function getUsedOutputFormat(): string
    {
        $option = "Used output format";
        return $this->getOptionString($option);
    }

    /**
     * Find the bitrate in kilobits.
     *
     * @return int
     */
    protected function getSelectedBitrate(): int
    {
        $option = "Selected bitrate";
        $text = $this->getOptionString($option);

        /* Convert 'xxxx kBit/s to int */
        return filter_var($text, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * Find the output quality that was used.
     *
     * @return string
     */
    protected function getOutputQuality(): string
    {
        $option = "Quality";
        return $this->getOptionString($option);
    }

    /**
     * Find whether ID3 tags were added or not.
     *
     * @return bool
     */
    protected function getID3TagsAdded(): bool
    {
        $option = "Add ID3 tag";
        return $this->getOptionBoolean($option);
    }

    /**
     * Get the executable that was used to compress the
     * .wav files.
     *
     * @return string
     */
    protected function getCompressorExecutable(): string
    {
        $option = "Command line compressor";
        return $this->getOptionString($option);
    }

    /**
     * Get the track data
     *
     *
     * @return array
     */
    protected function getTrackData(): array
    {
        /* Initialize variables required for while loop */
        $trackData = array();
        $trackFound = true;
        $trackNumber = 1;

        /* Loop until we have the data for every track */
        while ($trackFound) {

            /* Dirty hack for inconsistent spacing */
            if ($trackNumber < 10) {
                $pos = strpos($this->log, 'Track  ' . $trackNumber);
            } else {
                $pos = strpos($this->log, 'Track ' . $trackNumber);
            }

            /* If we didn't find a track, break the loop */
            if ($pos === FALSE) {
                $trackFound = false;
                break;
            }

            /* Create the track array */
            $track = [
                'filename' => $this->getTrackFileName($pos),
                /* Does not work correctly, yet */
                //'pregap_length' => $this->getTrackPregapLength($pos),
                'peak_level' => $this->getTrackPeakLevel($pos),
                'extraction_speed' => $this->getTrackExtractionSpeed($pos),
                'track_quality' => $this->getTrackQuality($pos),
                'test_crc' => $this->getTrackTestCrc($pos),
                'copy_crc' => $this->getTrackCopyCrc($pos),
                'accurate_rip_confidence' => $this->getTrackAccurateRipConfidence($pos),
                'copy' => $this->getTrackCopyResult($pos)
            ];

            $trackNumber++;

            array_push($trackData, $track);
        }

        return $trackData;
    }

    /**
     * Get the filename for a track starting at a given position
     *
     * @param int $pos
     * @return string
     */
    protected function getTrackFileName(int $pos): string
    {
        $option = 'Filename ';
        // TODO: Remove escaped slashes
        return $this->getTrackOption($pos, $option);
    }

    /**
     * Get the pregap length for a given track.
     *
     * @param int|string $pos
     * @return mixed
     */
    protected function getTrackPregapLength(int $pos): string
    {

        $option = 'Pre-gap length  ';
        return $this->getTrackOption($pos, $option);
    }

    /**
     * Gets the peak level for a given track.
     *
     * @param int $pos
     * @return float
     */
    protected function getTrackPeakLevel(int $pos): float
    {
        $option = 'Peak level ';
        $text = $this->getTrackOption($pos, $option);
        return filter_var($text, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    /**
     * Gets the extraction speed for a given track.
     *
     * @param int $pos
     * @return float
     */
    protected function getTrackExtractionSpeed(int $pos): float
    {
        $option = 'Extraction speed ';
        $text = $this->getTrackOption($pos, $option);
        return filter_var($text, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    /**
     * Gets the track quality for a given track.
     *
     * @param int $pos
     * @return float
     */
    protected function getTrackQuality(int $pos): float
    {
        $option = 'Track quality ';
        $text = $this->getTrackOption($pos, $option);
        return filter_var($text, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    /**
     * Gets the test CRC for a given track.
     *
     * @param int $pos
     * @return string
     */
    protected function getTrackTestCrc(int $pos): string
    {
        $option = 'Test CRC ';
        return $this->getTrackOption($pos, $option);
    }

    /**
     * Gets the copy CRC for a given track.
     *
     * @param int $pos
     * @return string
     */
    protected function getTrackCopyCrc(int $pos): string
    {
        $option = 'Copy CRC ';
        return $this->getTrackOption($pos, $option);
    }

    /**
     * Gets the AccurateRip confidence level for a given track.
     *
     * @param int $pos
     * @return int
     */
    protected function getTrackAccurateRipConfidence(int $pos): int
    {
        $option = 'confidence ';
        $pos = strpos($this->log, $option, $pos) + strlen($option);
        $text = substr($this->log, $pos, 1);

        if($text) {
            return filter_var($text, FILTER_SANITIZE_NUMBER_INT);
        }
        else {
            return 'not found';
        }

    }

    /**
     * Get the copy result for a given track.
     *
     * @param int $pos
     * @return string
     */
    protected function getTrackCopyResult(int $pos): string
    {
        $option = 'Copy OK';
        $result = strpos($this->log, $option, $pos);

        if(!$result)
        {
            return "Not found";
        }
        else {
            return "OK";
        }
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

    /**
     * Get the text after a colon
     *
     * @param $text
     * @return string
     */
    protected function getTextAfterColon($text): string
    {
        return substr($text, strpos($text, ':') + 2);
    }

    /**
     * Get the result for a given option
     *
     * @param $option
     * @return string
     */
    protected function getOptionString($option): string
    {
        /* Find the position at which we should start reading for the result */
        $pos = strpos($this->log, $option) + strlen($option);

        if ($pos === FALSE) {
            return $option . " not found";
        } /* Return the text after the colon on the line given */
        else {
            $text = $this->readFromPosToEOL($pos);
            return $this->getTextAfterColon($text);
        }
    }

    /**
     * Return a boolean for a text boolean.
     *
     * @param $option
     * @return bool
     */
    protected function getOptionBoolean($option): bool
    {
        if ($this->getOptionString($option) == "Yes") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int $pos
     * @param $option
     * @return string
     */
    protected function getTrackOption(int $pos, $option): string
    {
        $pos = strpos($this->log, $option, $pos) + strlen($option);
        return $this->readFromPosToEOL($pos);
    }
}