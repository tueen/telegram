<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\EphemeralMessageParameters;
use Tueen\Telegram\Types\ForceReply;
use Tueen\Telegram\Types\InlineKeyboardMarkup;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\ReplyKeyboardMarkup;
use Tueen\Telegram\Types\ReplyKeyboardRemove;
use Tueen\Telegram\Types\ReplyParameters;
use Tueen\Telegram\Types\SuggestedPostParameters;

/**
 * Use this method to send point on the map. On success, the sent Message is returned.
 *
 * @link https://core.telegram.org/bots/api#sendlocation
 */
#[ApiMethod('sendLocation', 'POST')]
#[ReturnType(Message::class, isArray: false)]
class SendLocation extends Method
{
    /**
     * Unique identifier for the target chat or username of the target bot, supergroup or channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * Latitude of the location
     */
    #[Field('latitude', required: true)]
    public ?float $latitude = null;

    /**
     * Longitude of the location
     */
    #[Field('longitude', required: true)]
    public ?float $longitude = null;

    /**
     * Unique identifier of the business connection on behalf of which the message will be sent
     */
    #[Field('business_connection_id', required: false)]
    public ?string $businessConnectionId = null;

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
     * A JSON-serialized object containing the parameters of the ephemeral message to send
     */
    #[Field('ephemeral_message_parameters', required: false)]
    public ?EphemeralMessageParameters $ephemeralMessageParameters = null;

    /**
     * The radius of uncertainty for the location, measured in meters; 0-1500
     */
    #[Field('horizontal_accuracy', required: false)]
    public ?float $horizontalAccuracy = null;

    /**
     * Period in seconds during which the location will be updated (see Live Locations), must be between 60 and 86400, or 0x7FFFFFFF for live locations that can be edited indefinitely. Must be 0 for ephemeral messages.
     */
    #[Field('live_period', required: false)]
    public ?int $livePeriod = null;

    /**
     * For live locations, a direction in which the user is moving, in degrees. Must be between 1 and 360 if specified.
     */
    #[Field('heading', required: false)]
    public ?int $heading = null;

    /**
     * For live locations, a maximum distance for proximity alerts about approaching another chat member, in meters. Must be between 1 and 100000 if specified.
     */
    #[Field('proximity_alert_radius', required: false)]
    public ?int $proximityAlertRadius = null;

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
     * Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard, instructions to remove a reply keyboard or to force a reply from the user.
     */
    #[Field('reply_markup', required: false)]
    public InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null;

    public function __construct(
        int|string|null $chatId = null,
        ?float $latitude = null,
        ?float $longitude = null,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?int $directMessagesTopicId = null,
        ?EphemeralMessageParameters $ephemeralMessageParameters = null,
        ?float $horizontalAccuracy = null,
        ?int $livePeriod = null,
        ?int $heading = null,
        ?int $proximityAlertRadius = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?bool $allowPaidBroadcast = null,
        ?string $messageEffectId = null,
        ?SuggestedPostParameters $suggestedPostParameters = null,
        ?ReplyParameters $replyParameters = null,
        InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($latitude !== null) $this->latitude = $latitude;
        if ($longitude !== null) $this->longitude = $longitude;
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($messageThreadId !== null) $this->messageThreadId = $messageThreadId;
        if ($directMessagesTopicId !== null) $this->directMessagesTopicId = $directMessagesTopicId;
        if ($ephemeralMessageParameters !== null) $this->ephemeralMessageParameters = $ephemeralMessageParameters;
        if ($horizontalAccuracy !== null) $this->horizontalAccuracy = $horizontalAccuracy;
        if ($livePeriod !== null) $this->livePeriod = $livePeriod;
        if ($heading !== null) $this->heading = $heading;
        if ($proximityAlertRadius !== null) $this->proximityAlertRadius = $proximityAlertRadius;
        if ($disableNotification !== null) $this->disableNotification = $disableNotification;
        if ($protectContent !== null) $this->protectContent = $protectContent;
        if ($allowPaidBroadcast !== null) $this->allowPaidBroadcast = $allowPaidBroadcast;
        if ($messageEffectId !== null) $this->messageEffectId = $messageEffectId;
        if ($suggestedPostParameters !== null) $this->suggestedPostParameters = $suggestedPostParameters;
        if ($replyParameters !== null) $this->replyParameters = $replyParameters;
        if ($replyMarkup !== null) $this->replyMarkup = $replyMarkup;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
