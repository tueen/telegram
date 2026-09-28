<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\Gifts;

/**
 * Returns the list of gifts that can be sent by the bot to users and channel chats. Requires no parameters. Returns a Gifts object.
 *
 * @link https://core.telegram.org/bots/api#getavailablegifts
 *
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('getAvailableGifts', 'POST')]
#[ReturnType(Gifts::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::FloodWait])]
class GetAvailableGifts extends Method
{


    public function __construct(
        mixed ...$extra
    )
    {
        if ($extra) $this->handleExtraParameters($extra);
    }
}
