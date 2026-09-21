<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\VideoQualityCodec;

/**
 * This object represents a video file of a specific quality.
 *
 * @link https://core.telegram.org/bots/api#videoquality
 */
class VideoQuality extends Type
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
     * Video width
     */
    #[Field('width', required: true)]
    public private(set) int $width;

    /**
     * Video height
     */
    #[Field('height', required: true)]
    public private(set) int $height;

    /**
     * Codec that was used to encode the video, for example, "h264", "h265", or "av01"
     */
    #[Field('codec', required: true)]
    public private(set) VideoQualityCodec|string $codec;

    /**
     * Optional. File size in bytes. It can be bigger than 2^31 and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this value.
     */
    #[Field('file_size', required: false)]
    public private(set) ?int $fileSize = null;

}
