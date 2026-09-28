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
 * Use this method to set the title of a created sticker set. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setstickersettitle
 *
 * @throws StickerSetInvalidException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('setStickerSetTitle', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::StickerSetInvalid, TelegramErrorCode::FloodWait])]
class SetStickerSetTitle extends Method
{
    /**
     * Sticker set name
     */
    #[Field('name', required: true)]
    public string $name;

    /**
     * Sticker set title, 1-64 characters
     */
    #[Field('title', required: true)]
    public string $title;

    public function __construct(
        string $name,
        string $title,
        mixed ...$extra
    )
    {
        $this->name = $name;
        $this->title = $title;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
