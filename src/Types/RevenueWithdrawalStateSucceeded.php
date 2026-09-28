<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

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
    private(set) RevenueWithdrawalStateType|string|null $type = null;

    /**
     * Date the withdrawal was completed in Unix time
     */
    #[Field('date', required: true)]
    private(set) ?int $date = null;

    /**
     * An HTTPS URL that can be used to see transaction details
     */
    #[Field('url', required: true)]
    private(set) ?string $url = null;

}
