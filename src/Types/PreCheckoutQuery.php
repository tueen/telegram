<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\User;
use Tueen\Telegram\Enums\Currency;
use Tueen\Telegram\Types\OrderInfo;

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
    public private(set) string $id;

    /**
     * User who sent the query
     */
    #[Field('from', required: true)]
    public private(set) User $from;

    /**
     * Three-letter ISO 4217 currency code, or "XTR" for payments in Telegram Stars
     */
    #[Field('currency', required: true)]
    public private(set) Currency|string $currency;

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
     * Optional. Identifier of the shipping option chosen by the user
     */
    #[Field('shipping_option_id', required: false)]
    public private(set) ?string $shippingOptionId = null;

    /**
     * Optional. Order information provided by the user
     */
    #[Field('order_info', required: false)]
    public private(set) ?OrderInfo $orderInfo = null;

}
