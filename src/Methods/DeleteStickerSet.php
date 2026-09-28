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
 * Use this method to delete a sticker set that was created by the bot. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#deletestickerset
 *
 * @throws StickerSetInvalidException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('deleteStickerSet', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::StickerSetInvalid, TelegramErrorCode::FloodWait])]
class DeleteStickerSet extends Method
{
    /**
     * Sticker set name
     */
    #[Field('name', required: true)]
    public string $name;

    public function __construct(
        string $name,
        mixed ...$extra
    )
    {
        $this->name = $name;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
