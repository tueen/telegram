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
 * Use this method to delete a sticker from a set created by the bot. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#deletestickerfromset
 *
 * @throws StickerSetInvalidException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('deleteStickerFromSet', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::StickerSetInvalid, TelegramErrorCode::FloodWait])]
class DeleteStickerFromSet extends Method
{
    /**
     * File identifier of the sticker
     */
    #[Field('sticker', required: true)]
    public string $sticker;

    public function __construct(
        string $sticker,
        mixed ...$extra
    )
    {
        $this->sticker = $sticker;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
