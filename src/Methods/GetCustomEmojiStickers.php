<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Sticker;

/**
 * Use this method to get information about custom emoji stickers by their identifiers. Returns an Array of Sticker objects.
 *
 * @link https://core.telegram.org/bots/api#getcustomemojistickers
 */
#[ApiMethod('getCustomEmojiStickers', 'POST')]
#[ReturnType(Sticker::class, isArray: true)]
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
        if ($customEmojiIds !== null) $this->customEmojiIds = $customEmojiIds;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
