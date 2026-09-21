<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RevenueWithdrawalStateType;

/**
 * The withdrawal succeeded.
 *
 * @link https://core.telegram.org/bots/api#revenuewithdrawalstatesucceeded
 */
class RevenueWithdrawalStateSucceeded extends RevenueWithdrawalState
{
    /**
     * Type of the state, always "succeeded"
     */
    #[Field('type', required: true)]
    public private(set) RevenueWithdrawalStateType|string $type;

    /**
     * Date the withdrawal was completed in Unix time
     */
    #[Field('date', required: true)]
    public private(set) int $date;

    /**
     * An HTTPS URL that can be used to see transaction details
     */
    #[Field('url', required: true)]
    public private(set) string $url;

}
