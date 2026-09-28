<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\StickerType;

/**
 * This object represents a sticker set.
 *
 * @link https://core.telegram.org/bots/api#stickerset
 */
class StickerSet extends Type
{
    /**
     * Sticker set name
     */
    #[Field('name', required: true)]
    private(set) string $name;

    /**
     * Sticker set title
     */
    #[Field('title', required: true)]
    private(set) string $title;

    /**
     * Type of stickers in the set, currently one of "regular", "mask", "custom_emoji"
     */
    #[Field('sticker_type', required: true)]
    private(set) StickerType|string $stickerType;

    /**
     * List of all set stickers
     * @var Sticker[]|null
     */
    #[Field('stickers', required: true)]
    #[ArrayOf(Sticker::class)]
    private(set) array $stickers;

    /**
     * Optional. Sticker set thumbnail in the .WEBP, .TGS, or .WEBM format
     */
    #[Field('thumbnail', required: false)]
    private(set) ?PhotoSize $thumbnail = null;

}
