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
use Tueen\Telegram\Types\Sticker;

/**
 * Use this method to get information about custom emoji stickers by their identifiers. Returns an Array of Sticker objects.
 *
 * @link https://core.telegram.org/bots/api#getcustomemojistickers
 *
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('getCustomEmojiStickers', 'POST')]
#[ReturnType(Sticker::class, isArray: true)]
#[ApiErrors([TelegramErrorCode::FloodWait])]
class GetCustomEmojiStickers extends Method
{
    /**
     * A JSON-serialized list of custom emoji identifiers. At most 200 custom emoji identifiers can be specified.
     */
    #[Field('custom_emoji_ids', required: true)]
    public array $customEmojiIds;

    public function __construct(
        array $customEmojiIds,
        mixed ...$extra
    )
    {
        $this->customEmojiIds = $customEmojiIds;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
