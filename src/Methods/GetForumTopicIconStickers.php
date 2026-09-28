<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\Sticker;

/**
 * Use this method to get custom emoji stickers, which can be used as a forum topic icon by any user. Requires no parameters. Returns an Array of Sticker objects.
 *
 * @link https://core.telegram.org/bots/api#getforumtopiciconstickers
 *
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('getForumTopicIconStickers', 'POST')]
#[ReturnType(Sticker::class, isArray: true)]
#[ApiErrors([TelegramErrorCode::FloodWait])]
class GetForumTopicIconStickers extends Method
{


    public function __construct(
        mixed ...$extra
    )
    {
        if ($extra) $this->handleExtraParameters($extra);
    }
}
