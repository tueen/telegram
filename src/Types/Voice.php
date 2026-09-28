<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a voice note.
 *
 * @link https://core.telegram.org/bots/api#voice
 */
class Voice extends Type
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
     * Duration of the audio in seconds as defined by the sender
     */
    #[Field('duration', required: true)]
    private(set) int $duration;

    /**
     * Optional. MIME type of the file as defined by the sender
     */
    #[Field('mime_type', required: false)]
    private(set) ?string $mimeType = null;

    /**
     * Optional. File size in bytes. It can be bigger than 2^31 and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this value.
     */
    #[Field('file_size', required: false)]
    private(set) ?int $fileSize = null;

}
