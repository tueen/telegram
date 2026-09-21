<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;

/**
 * Use this method to create a link for an invoice. Returns the created invoice link as String on success.
 *
 * @link https://core.telegram.org/bots/api#createinvoicelink
 */
#[ApiMethod('createInvoiceLink', 'POST')]
#[ReturnType(Type::class, isArray: false)]
class CreateInvoiceLink extends Method
{
    /**
     * Product name, 1-32 characters
     */
    #[Field('title', required: true)]
    public string $title;

    /**
     * Product description, 1-255 characters
     */
    #[Field('description', required: true)]
    public string $description;

    /**
     * Bot-defined invoice payload, 1-128 bytes. This will not be displayed to the user, use it for your internal processes.
     */
    #[Field('payload', required: true)]
    public string $payload;

    /**
     * Three-letter ISO 4217 currency code, see more on currencies. Pass "XTR" for payments in Telegram Stars.
     */
    #[Field('currency', required: true)]
    public string $currency;

    /**
     * Price breakdown, a JSON-serialized list of components (e.g. product price, tax, discount, delivery cost, delivery tax, bonus, etc.). Must contain exactly one item for payments in Telegram Stars.
     */
    #[Field('prices', required: true)]
    public array $prices;

    /**
     * Unique identifier of the business connection on behalf of which the link will be created. For payments in Telegram Stars only.
     */
    #[Field('business_connection_id', required: false)]
    public ?string $businessConnectionId = null;

    /**
     * Payment provider token, obtained via @BotFather. Pass an empty string for payments in Telegram Stars.
     */
    #[Field('provider_token', required: false)]
    public ?string $providerToken = null;

    /**
     * The number of seconds the subscription will be active for before the next payment. The currency must be set to "XTR" (Telegram Stars) if the parameter is used. Currently, it must always be 2592000 (30 days) if specified. Any number of subscriptions can be active for a given bot at the same time, including multiple concurrent subscriptions from the same user. Subscription price must no exceed 10000 Telegram Stars.
     */
    #[Field('subscription_period', required: false)]
    public ?int $subscriptionPeriod = null;

    /**
     * The maximum accepted amount for tips in the smallest units of the currency (integer, not float/double). For example, for a maximum tip of US$ 1.45 pass max_tip_amount = 145. See the exp parameter in currencies.json, it shows the number of digits past the decimal point for each currency (2 for the majority of currencies). Defaults to 0. Not supported for payments in Telegram Stars.
     */
    #[Field('max_tip_amount', required: false)]
    public ?int $maxTipAmount = null;

    /**
     * A JSON-serialized Array of suggested amounts of tips in the smallest units of the currency (integer, not float/double). At most 4 suggested tip amounts can be specified. The suggested tip amounts must be positive, passed in a strictly increased order and must not exceed max_tip_amount.
     */
    #[Field('suggested_tip_amounts', required: false)]
    public ?array $suggestedTipAmounts = null;

    /**
     * JSON-serialized data about the invoice, which will be shared with the payment provider. A detailed description of required fields should be provided by the payment provider.
     */
    #[Field('provider_data', required: false)]
    public ?string $providerData = null;

    /**
     * URL of the product photo for the invoice. Can be a photo of the goods or a marketing image for a service.
     */
    #[Field('photo_url', required: false)]
    public ?string $photoUrl = null;

    /**
     * Photo size in bytes
     */
    #[Field('photo_size', required: false)]
    public ?int $photoSize = null;

    /**
     * Photo width
     */
    #[Field('photo_width', required: false)]
    public ?int $photoWidth = null;

    /**
     * Photo height
     */
    #[Field('photo_height', required: false)]
    public ?int $photoHeight = null;

    /**
     * Pass True if you require the user's full name to complete the order. Ignored for payments in Telegram Stars.
     */
    #[Field('need_name', required: false)]
    public ?bool $needName = null;

    /**
     * Pass True if you require the user's phone number to complete the order. Ignored for payments in Telegram Stars.
     */
    #[Field('need_phone_number', required: false)]
    public ?bool $needPhoneNumber = null;

    /**
     * Pass True if you require the user's email address to complete the order. Ignored for payments in Telegram Stars.
     */
    #[Field('need_email', required: false)]
    public ?bool $needEmail = null;

    /**
     * Pass True if you require the user's shipping address to complete the order. Ignored for payments in Telegram Stars.
     */
    #[Field('need_shipping_address', required: false)]
    public ?bool $needShippingAddress = null;

    /**
     * Pass True if the user's phone number should be sent to the provider. Ignored for payments in Telegram Stars.
     */
    #[Field('send_phone_number_to_provider', required: false)]
    public ?bool $sendPhoneNumberToProvider = null;

    /**
     * Pass True if the user's email address should be sent to the provider. Ignored for payments in Telegram Stars.
     */
    #[Field('send_email_to_provider', required: false)]
    public ?bool $sendEmailToProvider = null;

    /**
     * Pass True if the final price depends on the shipping method. Ignored for payments in Telegram Stars.
     */
    #[Field('is_flexible', required: false)]
    public ?bool $isFlexible = null;

    public function __construct(
        string $title,
        string $description,
        string $payload,
        string $currency,
        array $prices,
        ?string $businessConnectionId = null,
        ?string $providerToken = null,
        ?int $subscriptionPeriod = null,
        ?int $maxTipAmount = null,
        ?array $suggestedTipAmounts = null,
        ?string $providerData = null,
        ?string $photoUrl = null,
        ?int $photoSize = null,
        ?int $photoWidth = null,
        ?int $photoHeight = null,
        ?bool $needName = null,
        ?bool $needPhoneNumber = null,
        ?bool $needEmail = null,
        ?bool $needShippingAddress = null,
        ?bool $sendPhoneNumberToProvider = null,
        ?bool $sendEmailToProvider = null,
        ?bool $isFlexible = null
    )
    {
        if ($title !== null) $this->title = $title;
        if ($description !== null) $this->description = $description;
        if ($payload !== null) $this->payload = $payload;
        if ($currency !== null) $this->currency = $currency;
        if ($prices !== null) $this->prices = $prices;
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($providerToken !== null) $this->providerToken = $providerToken;
        if ($subscriptionPeriod !== null) $this->subscriptionPeriod = $subscriptionPeriod;
        if ($maxTipAmount !== null) $this->maxTipAmount = $maxTipAmount;
        if ($suggestedTipAmounts !== null) $this->suggestedTipAmounts = $suggestedTipAmounts;
        if ($providerData !== null) $this->providerData = $providerData;
        if ($photoUrl !== null) $this->photoUrl = $photoUrl;
        if ($photoSize !== null) $this->photoSize = $photoSize;
        if ($photoWidth !== null) $this->photoWidth = $photoWidth;
        if ($photoHeight !== null) $this->photoHeight = $photoHeight;
        if ($needName !== null) $this->needName = $needName;
        if ($needPhoneNumber !== null) $this->needPhoneNumber = $needPhoneNumber;
        if ($needEmail !== null) $this->needEmail = $needEmail;
        if ($needShippingAddress !== null) $this->needShippingAddress = $needShippingAddress;
        if ($sendPhoneNumberToProvider !== null) $this->sendPhoneNumberToProvider = $sendPhoneNumberToProvider;
        if ($sendEmailToProvider !== null) $this->sendEmailToProvider = $sendEmailToProvider;
        if ($isFlexible !== null) $this->isFlexible = $isFlexible;
    }

    public static function make(
        string $title,
        string $description,
        string $payload,
        string $currency,
        array $prices,
        ?string $businessConnectionId = null,
        ?string $providerToken = null,
        ?int $subscriptionPeriod = null,
        ?int $maxTipAmount = null,
        ?array $suggestedTipAmounts = null,
        ?string $providerData = null,
        ?string $photoUrl = null,
        ?int $photoSize = null,
        ?int $photoWidth = null,
        ?int $photoHeight = null,
        ?bool $needName = null,
        ?bool $needPhoneNumber = null,
        ?bool $needEmail = null,
        ?bool $needShippingAddress = null,
        ?bool $sendPhoneNumberToProvider = null,
        ?bool $sendEmailToProvider = null,
        ?bool $isFlexible = null
    ): static
    {
        return new static($title, $description, $payload, $currency, $prices, $businessConnectionId, $providerToken, $subscriptionPeriod, $maxTipAmount, $suggestedTipAmounts, $providerData, $photoUrl, $photoSize, $photoWidth, $photoHeight, $needName, $needPhoneNumber, $needEmail, $needShippingAddress, $sendPhoneNumberToProvider, $sendEmailToProvider, $isFlexible);
    }
}
