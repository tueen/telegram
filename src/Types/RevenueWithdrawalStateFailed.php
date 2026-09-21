<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\RevenueWithdrawalStateType;

/**
 * The withdrawal failed and the transaction was refunded.
 *
 * @link https://core.telegram.org/bots/api#revenuewithdrawalstatefailed
 */
class RevenueWithdrawalStateFailed extends RevenueWithdrawalState
{
    /**
     * Type of the state, always "failed"
     */
    #[Field('type', required: true)]
    public private(set) RevenueWithdrawalStateType|string $type;

}
