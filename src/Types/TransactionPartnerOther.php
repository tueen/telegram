<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\TransactionPartnerType;

/**
 * Describes a transaction with an unknown source or recipient.
 *
 * @link https://core.telegram.org/bots/api#transactionpartnerother
 */
class TransactionPartnerOther extends TransactionPartner
{
    /**
     * Type of the transaction partner, always "other"
     */
    #[Field('type', required: true)]
    private(set) TransactionPartnerType|string $type;

}
