<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\PhotoSize;

/**
 * This object represents an animation file (GIF or H.264/MPEG-4 AVC video without sound).
 *
 * @link https://core.telegram.org/bots/api#animation
 */
class Animation extends Type
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
     * Video width as defined by the sender
     */
    #[Field('width', required: true)]
    public private(set) int $width;

    /**
     * Video height as defined by the sender
     */
    #[Field('height', required: true)]
    public private(set) int $height;

    /**
     * Duration of the video in seconds as defined by the sender
     */
    #[Field('duration', required: true)]
    public private(set) int $duration;

    /**
     * Optional. Animation thumbnail as defined by the sender
     */
    #[Field('thumbnail', required: false)]
    public private(set) ?PhotoSize $thumbnail = null;

    /**
     * Optional. Original animation filename as defined by the sender
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

}
