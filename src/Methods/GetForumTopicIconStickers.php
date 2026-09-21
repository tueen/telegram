<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Sticker;

/**
 * Use this method to get custom emoji stickers, which can be used as a forum topic icon by any user. Requires no parameters. Returns an Array of Sticker objects.
 *
 * @link https://core.telegram.org/bots/api#getforumtopiciconstickers
 */
#[ApiMethod('getForumTopicIconStickers', 'POST')]
#[ReturnType(Sticker::class, isArray: true)]
class GetForumTopicIconStickers extends Method
{


    public function __construct(
        mixed ...$extra
    )
    {
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
