<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\ReactionTypeType;

/**
 * The reaction is based on a custom emoji.
 *
 * @link https://core.telegram.org/bots/api#reactiontypecustomemoji
 */
class ReactionTypeCustomEmoji extends ReactionType
{
    /**
     * Type of the reaction, always "custom_emoji"
     */
    #[Field('type', required: true)]
    private(set) ReactionTypeType|string $type;

    /**
     * Custom emoji identifier
     */
    #[Field('custom_emoji_id', required: true)]
    private(set) string $customEmojiId;

}
