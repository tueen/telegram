<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\InputChecklist;
use Tueen\Telegram\Types\ReplyParameters;
use Tueen\Telegram\Types\InlineKeyboardMarkup;

/**
 * Use this method to send a checklist on behalf of a connected business account. On success, the sent Message is returned.
 *
 * @link https://core.telegram.org/bots/api#sendchecklist
 */
#[ApiMethod('sendChecklist', 'POST')]
#[ReturnType(Message::class, isArray: false)]
class SendChecklist extends Method
{
    /**
     * Unique identifier of the business connection on behalf of which the message will be sent
     */
    #[Field('business_connection_id', required: true)]
    public string $businessConnectionId;

    /**
     * Unique identifier for the target chat or username of the target bot in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * A JSON-serialized object for the checklist to send
     */
    #[Field('checklist', required: true)]
    public InputChecklist $checklist;

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
     * Unique identifier of the message effect to be added to the message
     */
    #[Field('message_effect_id', required: false)]
    public ?string $messageEffectId = null;

    /**
     * A JSON-serialized object for description of the message to reply to
     */
    #[Field('reply_parameters', required: false)]
    public ?ReplyParameters $replyParameters = null;

    /**
     * A JSON-serialized object for an inline keyboard
     */
    #[Field('reply_markup', required: false)]
    public ?InlineKeyboardMarkup $replyMarkup = null;

    public function __construct(
        string $businessConnectionId,
        int|string $chatId,
        InputChecklist $checklist,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        ?InlineKeyboardMarkup $replyMarkup = null
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($chatId !== null) $this->chatId = $chatId;
        if ($checklist !== null) $this->checklist = $checklist;
        if ($disableNotification !== null) $this->disableNotification = $disableNotification;
        if ($protectContent !== null) $this->protectContent = $protectContent;
        if ($messageEffectId !== null) $this->messageEffectId = $messageEffectId;
        if ($replyParameters !== null) $this->replyParameters = $replyParameters;
        if ($replyMarkup !== null) $this->replyMarkup = $replyMarkup;
    }

    public static function make(
        string $businessConnectionId,
        int|string $chatId,
        InputChecklist $checklist,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        ?InlineKeyboardMarkup $replyMarkup = null
    ): static
    {
        return new static($businessConnectionId, $chatId, $checklist, $disableNotification, $protectContent, $messageEffectId, $replyParameters, $replyMarkup);
    }
}
