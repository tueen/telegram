<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to set the thumbnail of a custom emoji sticker set. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setcustomemojistickersetthumbnail
 */
#[ApiMethod('setCustomEmojiStickerSetThumbnail', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SetCustomEmojiStickerSetThumbnail extends Method
{
    /**
     * Sticker set name
     */
    #[Field('name', required: true)]
    public string $name;

    /**
     * Custom emoji identifier of a sticker from the sticker set; pass an empty string to drop the thumbnail and use the first sticker as the thumbnail
     */
    #[Field('custom_emoji_id', required: false)]
    public ?string $customEmojiId = null;

    public function __construct(
        string $name,
        ?string $customEmojiId = null,
        mixed ...$extra
    )
    {
        if ($name !== null) $this->name = $name;
        if ($customEmojiId !== null) $this->customEmojiId = $customEmojiId;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
