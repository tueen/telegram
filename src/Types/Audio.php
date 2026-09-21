<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\PhotoSize;

/**
 * This object represents an audio file to be treated as music by the Telegram clients.
 *
 * @link https://core.telegram.org/bots/api#audio
 */
class Audio extends Type
{
    /**
     * Identifier for this file, which can be used to download or reuse the file
     */
    #[Field('file_id', required: true)]
    public private(set) string $fileId;

    /**
     * Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
     */
    #[Field('file_unique_id', required: true)]
    public private(set) string $fileUniqueId;

    /**
     * Duration of the audio in seconds as defined by the sender
     */
    #[Field('duration', required: true)]
    public private(set) int $duration;

    /**
     * Optional. Performer of the audio as defined by the sender or by audio tags
     */
    #[Field('performer', required: false)]
    public private(set) ?string $performer = null;

    /**
     * Optional. Title of the audio as defined by the sender or by audio tags
     */
    #[Field('title', required: false)]
    public private(set) ?string $title = null;

    /**
     * Optional. Original filename as defined by the sender
     */
    #[Field('file_name', required: false)]
    public private(set) ?string $fileName = null;

    /**
     * Optional. MIME type of the file as defined by the sender
     */
    #[Field('mime_type', required: false)]
    public private(set) ?string $mimeType = null;

    /**
     * Optional. File size in bytes. It can be bigger than 2^31 and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this value.
     */
    #[Field('file_size', required: false)]
    public private(set) ?int $fileSize = null;

    /**
     * Optional. Thumbnail of the album cover to which the music file belongs
     */
    #[Field('thumbnail', required: false)]
    public private(set) ?PhotoSize $thumbnail = null;

}
