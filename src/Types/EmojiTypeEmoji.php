<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * A regular emoji.
 *
 * @link https://core.telegram.org/bots/api#emojitypeemoji
 */
class EmojiTypeEmoji extends EmojiType
{
    /**
     * Type of the emoji, always "emoji"
     */
    #[Field('type', required: true)]
    private(set) ?string $type = null;

    /**
     * The emoji
     */
    #[Field('emoji', required: true)]
    private(set) ?string $emoji = null;

}
