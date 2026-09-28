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
use Tueen\Telegram\Exceptions\UnauthorizedException;
use Tueen\Telegram\Types\StickerSet;

/**
 * Use this method to get a sticker set. On success, a StickerSet object is returned.
 *
 * @link https://core.telegram.org/bots/api#getstickerset
 *
 * @throws StickerSetInvalidException
 * @throws UnauthorizedException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('getStickerSet', 'POST')]
#[ReturnType(StickerSet::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::StickerSetInvalid, TelegramErrorCode::Unauthorized, TelegramErrorCode::FloodWait])]
class GetStickerSet extends Method
{
    /**
     * Name of the sticker set
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
