<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object describes the source of a chat boost. It can be one of
 * - ChatBoostSourcePremium
 * - ChatBoostSourceGiftCode
 * - ChatBoostSourceGiveaway
 *
 * @link https://core.telegram.org/bots/api#chatboostsource
 */
class ChatBoostSource extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {
        if (($data['source'] ?? '') === 'premium') return ChatBoostSourcePremium::class;
        if (($data['source'] ?? '') === 'gift_code') return ChatBoostSourceGiftCode::class;
        if (($data['source'] ?? '') === 'giveaway') return ChatBoostSourceGiveaway::class;
        return static::class;
    }
}
