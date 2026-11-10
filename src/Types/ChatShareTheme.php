<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

/**
 * This object describes the theme of the screen for sharing a link to a user or a chat. Currently, it can be one of
 * - ChatShareThemeEmoji
 * - ChatShareThemeUniqueGift
 *
 * @link https://core.telegram.org/bots/api#chatsharetheme
 */
class ChatShareTheme extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {
        if (($data['type'] ?? '') === 'emoji') return ChatShareThemeEmoji::class;
        if (($data['type'] ?? '') === 'unique_gift') return ChatShareThemeUniqueGift::class;
        return static::class;
    }
}
