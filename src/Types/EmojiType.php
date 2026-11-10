<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

/**
 * This object describes an emoji. Currently, it can be one of
 * - EmojiTypeEmoji
 * - EmojiTypeCustomEmoji
 *
 * @link https://core.telegram.org/bots/api#emojitype
 */
class EmojiType extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {
        if (($data['type'] ?? '') === 'emoji') return EmojiTypeEmoji::class;
        if (($data['type'] ?? '') === 'custom_emoji') return EmojiTypeCustomEmoji::class;
        return static::class;
    }
}
