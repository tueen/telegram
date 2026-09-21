<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * This object describes a gift received and owned by a user or a chat. Currently, it can be one of
 * - OwnedGiftRegular
 * - OwnedGiftUnique
 *
 * @link https://core.telegram.org/bots/api#ownedgift
 */
class OwnedGift extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {
        if (($data['type'] ?? '') === 'regular') return OwnedGiftRegular::class;
        if (($data['type'] ?? '') === 'unique') return OwnedGiftUnique::class;
        return static::class;
    }
}
