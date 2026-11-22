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
use Tueen\Telegram\Types\BotFullInfo;

/**
 * A simple method for testing your bot's authentication token. Requires no parameters. Returns information about the bot in form of a BotFullInfo object.
 *
 * @link https://core.telegram.org/bots/api#getme
 *
 * @throws UnauthorizedException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('getMe', 'POST')]
#[ReturnType(BotFullInfo::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::Unauthorized, TelegramErrorCode::FloodWait])]
class GetMe extends Method
{


    public function __construct(
        mixed ...$extra
    )
    {
        if ($extra) $this->handleExtraParameters($extra);
    }
}
