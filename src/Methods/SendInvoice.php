<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Enums\Currency;
use Tueen\Telegram\Types\SuggestedPostParameters;
use Tueen\Telegram\Types\ReplyParameters;
use Tueen\Telegram\Types\InlineKeyboardMarkup;

/**
 * Use this method to send invoices. On success, the sent Message is returned.
 *
 * @link https://core.telegram.org/bots/api#sendinvoice
 */
#[ApiMethod('sendInvoice', 'POST')]
#[ReturnType(Message::class, isArray: false)]
class SendInvoice extends Method
{
    /**
     * Unique identifier for the target chat or username of the target bot, supergroup or channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

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
    public Currency|string $currency;

    /**
     * Price breakdown, a JSON-serialized list of components (e.g. product price, tax, discount, delivery cost, delivery tax, bonus, etc.). Must contain exactly one item for payments in Telegram Stars.
     */
    #[Field('prices', required: true)]
    public array $prices;

    /**
     * Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
     */
    #[Field('message_thread_id', required: false)]
    public ?int $messageThreadId = null;

    /**
     * Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
     */
    #[Field('direct_messages_topic_id', required: false)]
    public ?int $directMessagesTopicId = null;

    /**
     * Payment provider token, obtained via @BotFather. Pass an empty string for payments in Telegram Stars.
     */
    #[Field('provider_token', required: false)]
    public ?string $providerToken = null;

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
     * Unique deep-linking parameter. If left empty, forwarded copies of the sent message will have a Pay button, allowing multiple users to pay directly from the forwarded message, using the same invoice. If non-empty, forwarded copies of the sent message will have a URL button with a deep link to the bot (instead of a Pay button), with the value used as the start parameter.
     */
    #[Field('start_parameter', required: false)]
    public ?string $startParameter = null;

    /**
     * JSON-serialized data about the invoice, which will be shared with the payment provider. A detailed description of required fields should be provided by the payment provider.
     */
    #[Field('provider_data', required: false)]
    public ?string $providerData = null;

    /**
     * URL of the product photo for the invoice. Can be a photo of the goods or a marketing image for a service. People like it better when they see what they are paying for.
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

    /**
     * Sends the message silently. Users will receive a notification with no sound.
     */
    #[Field('disable_notification', required: false)]
    public ?bool $disableNotification = null;

    /**
     * Protects the contents of the sent message from forwarding and saving
     */
    #[Field('protect_content', required: false)]
    public ?bool $protectContent = null;

    /**
     * Pass True to allow up to 1000 messages per second, ignoring broadcasting limits for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
     */
    #[Field('allow_paid_broadcast', required: false)]
    public ?bool $allowPaidBroadcast = null;

    /**
     * Unique identifier of the message effect to be added to the message; for private chats only
     */
    #[Field('message_effect_id', required: false)]
    public ?string $messageEffectId = null;

    /**
     * A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
     */
    #[Field('suggested_post_parameters', required: false)]
    public ?SuggestedPostParameters $suggestedPostParameters = null;

    /**
     * Description of the message to reply to
     */
    #[Field('reply_parameters', required: false)]
    public ?ReplyParameters $replyParameters = null;

    /**
     * A JSON-serialized object for an inline keyboard. If empty, one 'Pay total price' button will be shown. If not empty, the first button must be a Pay button.
     */
    #[Field('reply_markup', required: false)]
    public ?InlineKeyboardMarkup $replyMarkup = null;

    public function __construct(
        int|string $chatId,
        string $title,
        string $description,
        string $payload,
        Currency|string $currency,
        array $prices,
        ?int $messageThreadId = null,
        ?int $directMessagesTopicId = null,
        ?string $providerToken = null,
        ?int $maxTipAmount = null,
        ?array $suggestedTipAmounts = null,
        ?string $startParameter = null,
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
        ?bool $isFlexible = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?bool $allowPaidBroadcast = null,
        ?string $messageEffectId = null,
        ?SuggestedPostParameters $suggestedPostParameters = null,
        ?ReplyParameters $replyParameters = null,
        ?InlineKeyboardMarkup $replyMarkup = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($title !== null) $this->title = $title;
        if ($description !== null) $this->description = $description;
        if ($payload !== null) $this->payload = $payload;
        if ($currency !== null) $this->currency = $currency;
        if ($prices !== null) $this->prices = $prices;
        if ($messageThreadId !== null) $this->messageThreadId = $messageThreadId;
        if ($directMessagesTopicId !== null) $this->directMessagesTopicId = $directMessagesTopicId;
        if ($providerToken !== null) $this->providerToken = $providerToken;
        if ($maxTipAmount !== null) $this->maxTipAmount = $maxTipAmount;
        if ($suggestedTipAmounts !== null) $this->suggestedTipAmounts = $suggestedTipAmounts;
        if ($startParameter !== null) $this->startParameter = $startParameter;
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
        if ($disableNotification !== null) $this->disableNotification = $disableNotification;
        if ($protectContent !== null) $this->protectContent = $protectContent;
        if ($allowPaidBroadcast !== null) $this->allowPaidBroadcast = $allowPaidBroadcast;
        if ($messageEffectId !== null) $this->messageEffectId = $messageEffectId;
        if ($suggestedPostParameters !== null) $this->suggestedPostParameters = $suggestedPostParameters;
        if ($replyParameters !== null) $this->replyParameters = $replyParameters;
        if ($replyMarkup !== null) $this->replyMarkup = $replyMarkup;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
