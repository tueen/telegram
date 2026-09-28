<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Gifts;

/**
 * Returns the list of gifts that can be sent by the bot to users and channel chats. Requires no parameters. Returns a Gifts object.
 *
 * @link https://core.telegram.org/bots/api#getavailablegifts
 */
#[ApiMethod('getAvailableGifts', 'POST')]
#[ReturnType(Gifts::class, isArray: false)]
class GetAvailableGifts extends Method
{


    public function __construct(
        mixed ...$extra
    )
    {
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
