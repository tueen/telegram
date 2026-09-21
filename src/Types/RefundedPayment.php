<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\Currency;

/**
 * This object contains basic information about a refunded payment.
 *
 * @link https://core.telegram.org/bots/api#refundedpayment
 */
class RefundedPayment extends Type
{
    /**
     * Three-letter ISO 4217 currency code, or "XTR" for payments in Telegram Stars. Currently, always "XTR".
     */
    #[Field('currency', required: true)]
    public private(set) Currency|string $currency;

    /**
     * Total refunded price in the smallest units of the currency (integer, not float/double). For example, for a price of US$ 1.45, total_amount = 145. See the exp parameter in currencies.json, it shows the number of digits past the decimal point for each currency (2 for the majority of currencies).
     */
    #[Field('total_amount', required: true)]
    public private(set) int $totalAmount;

    /**
     * Bot-specified invoice payload
     */
    #[Field('invoice_payload', required: true)]
    public private(set) string $invoicePayload;

    /**
     * Telegram payment identifier
     */
    #[Field('telegram_payment_charge_id', required: true)]
    public private(set) string $telegramPaymentChargeId;

    /**
     * Optional. Provider payment identifier
     */
    #[Field('provider_payment_charge_id', required: false)]
    public private(set) ?string $providerPaymentChargeId = null;

}
