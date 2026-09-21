<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;

/**
 * Use this method to change the list of emoji assigned to a regular or custom emoji sticker. The sticker must belong to a sticker set created by the bot. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setstickeremojilist
 */
#[ApiMethod('setStickerEmojiList', 'POST')]
#[ReturnType(Type::class, isArray: false)]
class SetStickerEmojiList extends Method
{
    /**
     * File identifier of the sticker
     */
    #[Field('sticker', required: true)]
    public string $sticker;

    /**
     * A JSON-serialized list of 1-20 emoji associated with the sticker
     */
    #[Field('emoji_list', required: true)]
    public array $emojiList;

    public function __construct(
        string $sticker,
        array $emojiList
    )
    {
        if ($sticker !== null) $this->sticker = $sticker;
        if ($emojiList !== null) $this->emojiList = $emojiList;
    }

    public static function make(
        string $sticker,
        array $emojiList
    ): static
    {
        return new static($sticker, $emojiList);
    }
}
