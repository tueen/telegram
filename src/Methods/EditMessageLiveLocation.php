<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\InlineKeyboardMarkup;
use Tueen\Telegram\Types\Message;

/**
 * Use this method to edit live location messages. A location can be edited until its live_period expires or editing is explicitly disabled by a call to stopMessageLiveLocation. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 *
 * @link https://core.telegram.org/bots/api#editmessagelivelocation
 */
#[ApiMethod('editMessageLiveLocation', 'POST')]
#[ReturnType(Message::class, isArray: false)]
class EditMessageLiveLocation extends Method
{
    /**
     * Latitude of new location
     */
    #[Field('latitude', required: true)]
    public float $latitude;

    /**
     * Longitude of new location
     */
    #[Field('longitude', required: true)]
    public float $longitude;

    /**
     * Unique identifier of the business connection on behalf of which the message to be edited was sent
     */
    #[Field('business_connection_id', required: false)]
    public ?string $businessConnectionId = null;

    /**
     * Required if inline_message_id is not specified. Unique identifier for the target chat or username of the target bot, supergroup or channel in the format @username.
     */
    #[Field('chat_id', required: false)]
    public int|string|null $chatId = null;

    /**
     * Required if inline_message_id is not specified. Identifier of the message to edit.
     */
    #[Field('message_id', required: false)]
    public ?int $messageId = null;

    /**
     * Required if chat_id and message_id are not specified. Identifier of the inline message.
     */
    #[Field('inline_message_id', required: false)]
    public ?string $inlineMessageId = null;

    /**
     * New period in seconds during which the location can be updated, starting from the message send date. If 0x7FFFFFFF is specified, then the location can be updated forever. Otherwise, the new value must not exceed the current live_period by more than a day, and the live location expiration date must remain within the next 90 days. If not specified, then live_period remains unchanged.
     */
    #[Field('live_period', required: false)]
    public ?int $livePeriod = null;

    /**
     * The radius of uncertainty for the location, measured in meters; 0-1500
     */
    #[Field('horizontal_accuracy', required: false)]
    public ?float $horizontalAccuracy = null;

    /**
     * Direction in which the user is moving, in degrees. Must be between 1 and 360 if specified.
     */
    #[Field('heading', required: false)]
    public ?int $heading = null;

    /**
     * The maximum distance for proximity alerts about approaching another chat member, in meters. Must be between 1 and 100000 if specified.
     */
    #[Field('proximity_alert_radius', required: false)]
    public ?int $proximityAlertRadius = null;

    /**
     * A JSON-serialized object for a new inline keyboard
     */
    #[Field('reply_markup', required: false)]
    public ?InlineKeyboardMarkup $replyMarkup = null;

    public function __construct(
        float $latitude,
        float $longitude,
        ?string $businessConnectionId = null,
        int|string|null $chatId = null,
        ?int $messageId = null,
        ?string $inlineMessageId = null,
        ?int $livePeriod = null,
        ?float $horizontalAccuracy = null,
        ?int $heading = null,
        ?int $proximityAlertRadius = null,
        ?InlineKeyboardMarkup $replyMarkup = null,
        mixed ...$extra
    )
    {
        if ($latitude !== null) $this->latitude = $latitude;
        if ($longitude !== null) $this->longitude = $longitude;
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($chatId !== null) $this->chatId = $chatId;
        if ($messageId !== null) $this->messageId = $messageId;
        if ($inlineMessageId !== null) $this->inlineMessageId = $inlineMessageId;
        if ($livePeriod !== null) $this->livePeriod = $livePeriod;
        if ($horizontalAccuracy !== null) $this->horizontalAccuracy = $horizontalAccuracy;
        if ($heading !== null) $this->heading = $heading;
        if ($proximityAlertRadius !== null) $this->proximityAlertRadius = $proximityAlertRadius;
        if ($replyMarkup !== null) $this->replyMarkup = $replyMarkup;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
