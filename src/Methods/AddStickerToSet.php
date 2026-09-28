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
use Tueen\Telegram\Exceptions\StickerDimensionsInvalidException;
use Tueen\Telegram\Exceptions\StickerEmojiInvalidException;
use Tueen\Telegram\Exceptions\StickerSetInvalidException;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Types\InputSticker;

/**
 * Use this method to add a new sticker to a set created by the bot. Emoji sticker sets can have up to 200 stickers. Other sticker sets can have up to 120 stickers. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#addstickertoset
 *
 * @throws StickerSetInvalidException
 * @throws StickerEmojiInvalidException
 * @throws StickerDimensionsInvalidException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('addStickerToSet', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::StickerSetInvalid, TelegramErrorCode::StickerEmojiInvalid, TelegramErrorCode::StickerDimensionsInvalid, TelegramErrorCode::FloodWait])]
class AddStickerToSet extends Method
{
    /**
     * User identifier of sticker set owner
     */
    #[Field('user_id', required: true)]
    public ?int $userId = null;

    /**
     * Sticker set name
     */
    #[Field('name', required: true)]
    public ?string $name = null;

    /**
     * A JSON-serialized object with information about the added sticker. If exactly the same sticker had already been added to the set, then the set isn't changed.
     */
    #[Field('sticker', required: true)]
    public ?InputSticker $sticker = null;

    public function __construct(
        ?int $userId = null,
        ?string $name = null,
        ?InputSticker $sticker = null,
        mixed ...$extra
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($name !== null) $this->name = $name;
        if ($sticker !== null) $this->sticker = $sticker;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
