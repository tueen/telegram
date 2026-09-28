<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents one size of a photo or a file / sticker thumbnail.
 *
 * @link https://core.telegram.org/bots/api#photosize
 */
class PhotoSize extends Type
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
     * Photo width
     */
    #[Field('width', required: true)]
    private(set) ?int $width = null;

    /**
     * Photo height
     */
    #[Field('height', required: true)]
    private(set) ?int $height = null;

    /**
     * Optional. File size in bytes
     */
    #[Field('file_size', required: false)]
    private(set) ?int $fileSize = null;

}
