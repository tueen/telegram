<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Removes the profile photo of the bot. Requires no parameters. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#removemyprofilephoto
 *
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('removeMyProfilePhoto', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::FloodWait])]
class RemoveMyProfilePhoto extends Method
{


    public function __construct(
        mixed ...$extra
    )
    {
        if ($extra) $this->handleExtraParameters($extra);
    }
}
