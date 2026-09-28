<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\Currency;

/**
 * This object contains information about an incoming pre-checkout query.
 *
 * @link https://core.telegram.org/bots/api#precheckoutquery
 */
class PreCheckoutQuery extends Type
{
    /**
     * Unique query identifier
     */
    #[Field('id', required: true)]
    private(set) string $id;

    /**
     * User who sent the query
     */
    #[Field('from', required: true)]
    private(set) User $from;

    /**
     * Three-letter ISO 4217 currency code, or "XTR" for payments in Telegram Stars
     */
    #[Field('currency', required: true)]
    private(set) Currency|string $currency;

    /**
     * Total price in the smallest units of the currency (integer, not float/double). For example, for a price of US$ 1.45 pass amount = 145. See the exp parameter in currencies.json, it shows the number of digits past the decimal point for each currency (2 for the majority of currencies).
     */
    #[Field('total_amount', required: true)]
    private(set) int $totalAmount;

    /**
     * Bot-specified invoice payload
     */
    #[Field('invoice_payload', required: true)]
    private(set) string $invoicePayload;

    /**
     * Optional. Identifier of the shipping option chosen by the user
     */
    #[Field('shipping_option_id', required: false)]
    private(set) ?string $shippingOptionId = null;

    /**
     * Optional. Order information provided by the user
     */
    #[Field('order_info', required: false)]
    private(set) ?OrderInfo $orderInfo = null;

}
