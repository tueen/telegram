<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\InputChecklist;
use Tueen\Telegram\Types\InlineKeyboardMarkup;

/**
 * Use this method to edit a checklist on behalf of a connected business account. On success, the edited Message is returned.
 *
 * @link https://core.telegram.org/bots/api#editmessagechecklist
 */
#[ApiMethod('editMessageChecklist', 'POST')]
#[ReturnType(Message::class, isArray: false)]
class EditMessageChecklist extends Method
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
     * Unique identifier for the target message
     */
    #[Field('message_id', required: true)]
    public int $messageId;

    /**
     * A JSON-serialized object for the new checklist
     */
    #[Field('checklist', required: true)]
    public InputChecklist $checklist;

    /**
     * A JSON-serialized object for the new inline keyboard for the message
     */
    #[Field('reply_markup', required: false)]
    public ?InlineKeyboardMarkup $replyMarkup = null;

    public function __construct(
        string $businessConnectionId,
        int|string $chatId,
        int $messageId,
        InputChecklist $checklist,
        ?InlineKeyboardMarkup $replyMarkup = null,
        mixed ...$extra
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($chatId !== null) $this->chatId = $chatId;
        if ($messageId !== null) $this->messageId = $messageId;
        if ($checklist !== null) $this->checklist = $checklist;
        if ($replyMarkup !== null) $this->replyMarkup = $replyMarkup;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
