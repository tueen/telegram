<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Exceptions\UnauthorizedException;
use Tueen\Telegram\Types\StarAmount;

/**
 * A method to get the current Telegram Stars balance of the bot. Requires no parameters. On success, returns a StarAmount object.
 *
 * @link https://core.telegram.org/bots/api#getmystarbalance
 *
 * @throws UnauthorizedException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('getMyStarBalance', 'POST')]
#[ReturnType(StarAmount::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::Unauthorized, TelegramErrorCode::FloodWait])]
class GetMyStarBalance extends Method
{


    public function __construct(
        mixed ...$extra
    )
    {
        if ($extra) $this->handleExtraParameters($extra);
    }
}
