<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

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
    public private(set) string $type;

}
