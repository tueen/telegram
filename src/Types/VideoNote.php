<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a video message.
 *
 * @link https://core.telegram.org/bots/api#videonote
 */
class VideoNote extends Type
{
    /**
     * Identifier for this file, which can be used to download or reuse the file
     */
    #[Field('file_id', required: true)]
    private(set) ?string $fileId = null;

    /**
     * Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
     */
    #[Field('file_unique_id', required: true)]
    private(set) ?string $fileUniqueId = null;

    /**
     * Video width and height (diameter of the video message) as defined by the sender
     */
    #[Field('length', required: true)]
    private(set) ?int $length = null;

    /**
     * Duration of the video in seconds as defined by the sender
     */
    #[Field('duration', required: true)]
    private(set) ?int $duration = null;

    /**
     * Optional. Video thumbnail
     */
    #[Field('thumbnail', required: false)]
    private(set) ?PhotoSize $thumbnail = null;

    /**
     * Optional. File size in bytes
     */
    #[Field('file_size', required: false)]
    private(set) ?int $fileSize = null;

}
