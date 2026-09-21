<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * A custom emoji.
 *
 * @link https://core.telegram.org/bots/api#richtextcustomemoji
 */
class RichTextCustomEmoji extends RichText
{
    /**
     * Type of the rich text, always "custom_emoji"
     */
    #[Field('type', required: true)]
    public private(set) string $type;

    /**
     * Unique identifier of the custom emoji. Use getCustomEmojiStickers to get full information about the sticker.
     */
    #[Field('custom_emoji_id', required: true)]
    public private(set) string $customEmojiId;

    /**
     * Alternative emoji for the custom emoji
     */
    #[Field('alternative_text', required: true)]
    public private(set) string $alternativeText;

}
