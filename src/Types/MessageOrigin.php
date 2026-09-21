<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object describes the origin of a message. It can be one of
 * - MessageOriginUser
 * - MessageOriginHiddenUser
 * - MessageOriginChat
 * - MessageOriginChannel
 *
 * @link https://core.telegram.org/bots/api#messageorigin
 */
class MessageOrigin extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {
        if (($data['type'] ?? '') === 'user') return MessageOriginUser::class;
        if (($data['type'] ?? '') === 'hidden_user') return MessageOriginHiddenUser::class;
        if (($data['type'] ?? '') === 'chat') return MessageOriginChat::class;
        if (($data['type'] ?? '') === 'channel') return MessageOriginChannel::class;
        return static::class;
    }
}
