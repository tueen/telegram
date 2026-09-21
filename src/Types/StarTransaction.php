<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Describes a Telegram Star transaction. Note that if the buyer initiates a chargeback with the payment provider from whom they acquired Stars (e.g., Apple, Google) following this transaction, the refunded Stars will be deducted from the bot's balance. This is outside of Telegram's control.
 *
 * @link https://core.telegram.org/bots/api#startransaction
 */
class StarTransaction extends Type
{
    /**
     * Unique identifier of the transaction. Coincides with the identifier of the original transaction for refund transactions. Coincides with SuccessfulPayment.telegram_payment_charge_id for successful incoming payments from users.
     */
    #[Field('id', required: true)]
    public private(set) string $id;

    /**
     * Integer amount of Telegram Stars transferred by the transaction
     */
    #[Field('amount', required: true)]
    public private(set) int $amount;

    /**
     * Optional. The number of 1/1000000000 shares of Telegram Stars transferred by the transaction; from 0 to 999999999
     */
    #[Field('nanostar_amount', required: false)]
    public private(set) ?int $nanostarAmount = null;

    /**
     * Date the transaction was created in Unix time
     */
    #[Field('date', required: true)]
    public private(set) int $date;

    /**
     * Optional. Source of an incoming transaction (e.g., a user purchasing goods or services, Fragment refunding a failed withdrawal). Only for incoming transactions.
     */
    #[Field('source', required: false)]
    public private(set) ?TransactionPartner $source = null;

    /**
     * Optional. Receiver of an outgoing transaction (e.g., a user for a purchase refund, Fragment for a withdrawal). Only for outgoing transactions.
     */
    #[Field('receiver', required: false)]
    public private(set) ?TransactionPartner $receiver = null;

}
