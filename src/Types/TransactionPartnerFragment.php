<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\TransactionPartnerType;
use Tueen\Telegram\Types\RevenueWithdrawalState;

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
    public private(set) TransactionPartnerType|string $type;

    /**
     * Optional. State of the transaction if the transaction is outgoing
     */
    #[Field('withdrawal_state', required: false)]
    public private(set) ?RevenueWithdrawalState $withdrawalState = null;

}
