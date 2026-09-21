<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\Currency;
use Tueen\Telegram\Types\LabeledPrice;

/**
 * Represents the content of an invoice message to be sent as the result of an inline query.
 *
 * @link https://core.telegram.org/bots/api#inputinvoicemessagecontent
 */
class InputInvoiceMessageContent extends InputMessageContent
{
    /**
     * Product name, 1-32 characters
     */
    #[Field('title', required: true)]
    public private(set) string $title;

    /**
     * Product description, 1-255 characters
     */
    #[Field('description', required: true)]
    public private(set) string $description;

    /**
     * Bot-defined invoice payload, 1-128 bytes. This will not be displayed to the user, use it for your internal processes.
     */
    #[Field('payload', required: true)]
    public private(set) string $payload;

    /**
     * Optional. Payment provider token, obtained via @BotFather. Pass an empty string for payments in Telegram Stars.
     */
    #[Field('provider_token', required: false)]
    public private(set) ?string $providerToken = null;

    /**
     * Three-letter ISO 4217 currency code, see more on currencies. Pass "XTR" for payments in Telegram Stars.
     */
    #[Field('currency', required: true)]
    public private(set) Currency|string $currency;

    /**
     * Price breakdown, a JSON-serialized list of components (e.g. product price, tax, discount, delivery cost, delivery tax, bonus, etc.). Must contain exactly one item for payments in Telegram Stars.
     * @var LabeledPrice[]|null
     */
    #[Field('prices', required: true)]
    #[ArrayOf(LabeledPrice::class)]
    public private(set) array $prices;

    /**
     * Optional. The maximum accepted amount for tips in the smallest units of the currency (integer, not float/double). For example, for a maximum tip of US$ 1.45 pass max_tip_amount = 145. See the exp parameter in currencies.json, it shows the number of digits past the decimal point for each currency (2 for the majority of currencies). Defaults to 0. Not supported for payments in Telegram Stars.
     */
    #[Field('max_tip_amount', required: false)]
    public private(set) ?int $maxTipAmount = null;

    /**
     * Optional. A JSON-serialized Array of suggested amounts of tip in the smallest units of the currency (integer, not float/double). At most 4 suggested tip amounts can be specified. The suggested tip amounts must be positive, passed in a strictly increased order and must not exceed max_tip_amount.
     * @var Integer[]|null
     */
    #[Field('suggested_tip_amounts', required: false)]
    public private(set) ?array $suggestedTipAmounts = null;

    /**
     * Optional. A JSON-serialized object for data about the invoice, which will be shared with the payment provider. A detailed description of the required fields should be provided by the payment provider.
     */
    #[Field('provider_data', required: false)]
    public private(set) ?string $providerData = null;

    /**
     * Optional. URL of the product photo for the invoice. Can be a photo of the goods or a marketing image for a service.
     */
    #[Field('photo_url', required: false)]
    public private(set) ?string $photoUrl = null;

    /**
     * Optional. Photo size in bytes
     */
    #[Field('photo_size', required: false)]
    public private(set) ?int $photoSize = null;

    /**
     * Optional. Photo width
     */
    #[Field('photo_width', required: false)]
    public private(set) ?int $photoWidth = null;

    /**
     * Optional. Photo height
     */
    #[Field('photo_height', required: false)]
    public private(set) ?int $photoHeight = null;

    /**
     * Optional. Pass True if you require the user's full name to complete the order. Ignored for payments in Telegram Stars.
     */
    #[Field('need_name', required: false)]
    public private(set) ?bool $needName = null;

    /**
     * Optional. Pass True if you require the user's phone number to complete the order. Ignored for payments in Telegram Stars.
     */
    #[Field('need_phone_number', required: false)]
    public private(set) ?bool $needPhoneNumber = null;

    /**
     * Optional. Pass True if you require the user's email address to complete the order. Ignored for payments in Telegram Stars.
     */
    #[Field('need_email', required: false)]
    public private(set) ?bool $needEmail = null;

    /**
     * Optional. Pass True if you require the user's shipping address to complete the order. Ignored for payments in Telegram Stars.
     */
    #[Field('need_shipping_address', required: false)]
    public private(set) ?bool $needShippingAddress = null;

    /**
     * Optional. Pass True if the user's phone number should be sent to the provider. Ignored for payments in Telegram Stars.
     */
    #[Field('send_phone_number_to_provider', required: false)]
    public private(set) ?bool $sendPhoneNumberToProvider = null;

    /**
     * Optional. Pass True if the user's email address should be sent to the provider. Ignored for payments in Telegram Stars.
     */
    #[Field('send_email_to_provider', required: false)]
    public private(set) ?bool $sendEmailToProvider = null;

    /**
     * Optional. Pass True if the final price depends on the shipping method. Ignored for payments in Telegram Stars.
     */
    #[Field('is_flexible', required: false)]
    public private(set) ?bool $isFlexible = null;

}
