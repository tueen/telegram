<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Exceptions\StickerSetInvalidException;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to set the thumbnail of a custom emoji sticker set. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setcustomemojistickersetthumbnail
 *
 * @throws StickerSetInvalidException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('setCustomEmojiStickerSetThumbnail', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::StickerSetInvalid, TelegramErrorCode::FloodWait])]
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
        $this->name = $name;
        if ($customEmojiId !== null) $this->customEmojiId = $customEmojiId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
