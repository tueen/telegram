<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * This object describes a message that can be inaccessible to the bot. It can be one of
 * - Message
 * - InaccessibleMessage
 *
 * @link https://core.telegram.org/bots/api#maybeinaccessiblemessage
 */
class MaybeInaccessibleMessage extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {
        if (isset($data['date']) && (int)$data['date'] === 0) {
            return InaccessibleMessage::class;
        }

        return Message::class;
    }
}
