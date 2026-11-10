<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\ChatShareThemeBackdrops;

/**
 * Use this method to get the list of backdrops, which can be used as the background of a theme of the screen for sharing a link to a user or a chat. Requires no parameters. Returns a ChatShareThemeBackdrops object.
 *
 * @link https://core.telegram.org/bots/api#getchatsharethemebackdrops
 */
#[ApiMethod('getChatShareThemeBackdrops', 'POST')]
#[ReturnType(ChatShareThemeBackdrops::class, isArray: false)]
class GetChatShareThemeBackdrops extends Method
{


    public function __construct(
        mixed ...$extra
    )
    {
        if ($extra) $this->handleExtraParameters($extra);
    }
}
