<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object describes the state of a revenue withdrawal operation. Currently, it can be one of
 * - RevenueWithdrawalStatePending
 * - RevenueWithdrawalStateSucceeded
 * - RevenueWithdrawalStateFailed
 *
 * @link https://core.telegram.org/bots/api#revenuewithdrawalstate
 */
class RevenueWithdrawalState extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {
        if (($data['type'] ?? '') === 'pending') return RevenueWithdrawalStatePending::class;
        if (($data['type'] ?? '') === 'succeeded') return RevenueWithdrawalStateSucceeded::class;
        if (($data['type'] ?? '') === 'failed') return RevenueWithdrawalStateFailed::class;
        return static::class;
    }
}
