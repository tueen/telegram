<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\Currency;

/**
 * This object contains basic information about an invoice.
 *
 * @link https://core.telegram.org/bots/api#invoice
 */
class Invoice extends Type
{
    /**
     * Product name
     */
    #[Field('title', required: true)]
    public private(set) string $title;

    /**
     * Product description
     */
    #[Field('description', required: true)]
    public private(set) string $description;

    /**
     * Unique bot deep-linking parameter that can be used to generate this invoice
     */
    #[Field('start_parameter', required: true)]
    public private(set) string $startParameter;

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

}
