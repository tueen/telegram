<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

/**
 * This object describes the type of a reaction. Currently, it can be one of
 * - ReactionTypeEmoji
 * - ReactionTypeCustomEmoji
 * - ReactionTypePaid
 *
 * @link https://core.telegram.org/bots/api#reactiontype
 */
class ReactionType extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {
        if (($data['type'] ?? '') === 'emoji') return ReactionTypeEmoji::class;
        if (($data['type'] ?? '') === 'custom_emoji') return ReactionTypeCustomEmoji::class;
        if (($data['type'] ?? '') === 'paid') return ReactionTypePaid::class;
        return static::class;
    }
}
