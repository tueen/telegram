<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\TransactionPartnerType;

/**
 * Describes a withdrawal transaction with Fragment.
 *
 * @link https://core.telegram.org/bots/api#transactionpartnerfragment
 */
class TransactionPartnerFragment extends TransactionPartner
{
    /**
     * Type of the transaction partner, always "fragment"
     */
    #[Field('type', required: true)]
    private(set) TransactionPartnerType|string $type;

    /**
     * Optional. State of the transaction if the transaction is outgoing
     */
    #[Field('withdrawal_state', required: false)]
    private(set) ?RevenueWithdrawalState $withdrawalState = null;

}
