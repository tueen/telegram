<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\OrderInfo;

/**
 * This object contains basic information about a successful payment. Note that if the buyer initiates a chargeback with the relevant payment provider following this transaction, the funds may be debited from your balance. This is outside of Telegram's control.
 *
 * @link https://core.telegram.org/bots/api#successfulpayment
 */
class SuccessfulPayment extends Type
{
    /**
     * Three-letter ISO 4217 currency code, or "XTR" for payments in Telegram Stars
     */
    #[Field('currency', required: true)]
    public private(set) string $currency;

    /**
     * Total price in the smallest units of the currency (integer, not float/double). For example, for a price of US$ 1.45 pass amount = 145. See the exp parameter in currencies.json, it shows the number of digits past the decimal point for each currency (2 for the majority of currencies).
     */
    #[Field('total_amount', required: true)]
    public private(set) int $totalAmount;

    /**
     * Bot-specified invoice payload
     */
    #[Field('invoice_payload', required: true)]
    public private(set) string $invoicePayload;

    /**
     * Optional. Expiration date of the subscription, in Unix time; for recurring payments only
     */
    #[Field('subscription_expiration_date', required: false)]
    public private(set) ?int $subscriptionExpirationDate = null;

    /**
     * Optional. True, if the payment is a recurring payment for a subscription
     */
    #[Field('is_recurring', required: false)]
    public private(set) ?bool $isRecurring = null;

    /**
     * Optional. True, if the payment is the first payment for a subscription
     */
    #[Field('is_first_recurring', required: false)]
    public private(set) ?bool $isFirstRecurring = null;

    /**
     * Optional. Identifier of the shipping option chosen by the user
     */
    #[Field('shipping_option_id', required: false)]
    public private(set) ?string $shippingOptionId = null;

    /**
     * Optional. Order information provided by the user
     */
    #[Field('order_info', required: false)]
    public private(set) ?OrderInfo $orderInfo = null;

    /**
     * Telegram payment identifier
     */
    #[Field('telegram_payment_charge_id', required: true)]
    public private(set) string $telegramPaymentChargeId;

    /**
     * Provider payment identifier
     */
    #[Field('provider_payment_charge_id', required: true)]
    public private(set) string $providerPaymentChargeId;

}
