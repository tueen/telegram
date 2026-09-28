<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RevenueWithdrawalStateType;

/**
 * The withdrawal is in progress.
 *
 * @link https://core.telegram.org/bots/api#revenuewithdrawalstatepending
 */
class RevenueWithdrawalStatePending extends RevenueWithdrawalState
{
    /**
     * Type of the state, always "pending"
     */
    #[Field('type', required: true)]
    private(set) RevenueWithdrawalStateType|string|null $type = null;

}
