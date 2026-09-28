<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a file uploaded to Telegram Passport. Currently all Telegram Passport files are in JPEG format when decrypted and don't exceed 10MB.
 *
 * @link https://core.telegram.org/bots/api#passportfile
 */
class PassportFile extends Type
{
    /**
     * Identifier for this file, which can be used to download or reuse the file
     */
    #[Field('file_id', required: true)]
    private(set) string $fileId;

    /**
     * Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
     */
    #[Field('file_unique_id', required: true)]
    private(set) string $fileUniqueId;

    /**
     * File size in bytes
     */
    #[Field('file_size', required: true)]
    private(set) int $fileSize;

    /**
     * Unix time when the file was uploaded
     */
    #[Field('file_date', required: true)]
    private(set) int $fileDate;

}
