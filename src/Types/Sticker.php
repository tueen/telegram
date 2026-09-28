<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\StickerType;

/**
 * This object represents a sticker.
 *
 * @link https://core.telegram.org/bots/api#sticker
 */
class Sticker extends Type
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
     * Type of the sticker, currently one of "regular", "mask", "custom_emoji". The type of the sticker is independent from its format, which is determined by the fields is_animated and is_video.
     */
    #[Field('type', required: true)]
    private(set) StickerType|string $type;

    /**
     * Sticker width
     */
    #[Field('width', required: true)]
    private(set) int $width;

    /**
     * Sticker height
     */
    #[Field('height', required: true)]
    private(set) int $height;

    /**
     * True, if the sticker is animated
     */
    #[Field('is_animated', required: true)]
    private(set) bool $isAnimated;

    /**
     * True, if the sticker is a video sticker
     */
    #[Field('is_video', required: true)]
    private(set) bool $isVideo;

    /**
     * Optional. Sticker thumbnail in the .WEBP or .JPG format
     */
    #[Field('thumbnail', required: false)]
    private(set) ?PhotoSize $thumbnail = null;

    /**
     * Optional. Emoji associated with the sticker
     */
    #[Field('emoji', required: false)]
    private(set) ?string $emoji = null;

    /**
     * Optional. Name of the sticker set to which the sticker belongs
     */
    #[Field('set_name', required: false)]
    private(set) ?string $setName = null;

    /**
     * Optional. For premium regular stickers, premium animation for the sticker
     */
    #[Field('premium_animation', required: false)]
    private(set) ?File $premiumAnimation = null;

    /**
     * Optional. For mask stickers, the position where the mask should be placed
     */
    #[Field('mask_position', required: false)]
    private(set) ?MaskPosition $maskPosition = null;

    /**
     * Optional. For custom emoji stickers, unique identifier of the custom emoji
     */
    #[Field('custom_emoji_id', required: false)]
    private(set) ?string $customEmojiId = null;

    /**
     * Optional. True, if the sticker must be repainted to a text color in messages, the color of the Telegram Premium badge in emoji status, white color on chat photos, or another appropriate color in other places
     */
    #[Field('needs_repainting', required: false)]
    private(set) ?bool $needsRepainting = null;

    /**
     * Optional. File size in bytes
     */
    #[Field('file_size', required: false)]
    private(set) ?int $fileSize = null;

}
