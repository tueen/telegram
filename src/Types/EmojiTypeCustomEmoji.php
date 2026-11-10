<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * A custom emoji.
 *
 * @link https://core.telegram.org/bots/api#emojitypecustomemoji
 */
class EmojiTypeCustomEmoji extends EmojiType
{
    /**
     * Type of the emoji, always "custom_emoji"
     */
    #[Field('type', required: true)]
    private(set) ?string $type = null;

    /**
     * Identifier of the custom emoji. Use getCustomEmojiStickers to get full information about the sticker.
     */
    #[Field('custom_emoji_id', required: true)]
    private(set) ?string $customEmojiId = null;

}
