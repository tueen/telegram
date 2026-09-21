<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\StarAmount;

/**
 * A method to get the current Telegram Stars balance of the bot. Requires no parameters. On success, returns a StarAmount object.
 *
 * @link https://core.telegram.org/bots/api#getmystarbalance
 */
#[ApiMethod('getMyStarBalance', 'POST')]
#[ReturnType(StarAmount::class, isArray: false)]
class GetMyStarBalance extends Method
{


    public function __construct(
        mixed ...$extra
    )
    {
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
