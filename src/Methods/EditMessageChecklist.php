<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\ChatNotFoundException;
use Tueen\Telegram\Exceptions\MessageCantBeEditedException;
use Tueen\Telegram\Exceptions\MessageNotFoundException;
use Tueen\Telegram\Exceptions\MessageNotModifiedException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\InlineKeyboardMarkup;
use Tueen\Telegram\Types\InputChecklist;
use Tueen\Telegram\Types\Message;

/**
 * Use this method to edit a checklist on behalf of a connected business account. On success, the edited Message is returned.
 *
 * @link https://core.telegram.org/bots/api#editmessagechecklist
 *
 * @throws ChatNotFoundException
 * @throws MessageNotFoundException
 * @throws MessageNotModifiedException
 * @throws MessageCantBeEditedException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('editMessageChecklist', 'POST')]
#[ReturnType(Message::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::ChatNotFound, TelegramErrorCode::MessageNotFound, TelegramErrorCode::MessageNotModified, TelegramErrorCode::MessageCantBeEdited, TelegramErrorCode::FloodWait])]
class EditMessageChecklist extends Method
{
    /**
     * Unique identifier of the business connection on behalf of which the message will be sent
     */
    #[Field('business_connection_id', required: true)]
    public ?string $businessConnectionId = null;

    /**
     * Unique identifier for the target chat or username of the target bot in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * Unique identifier for the target message
     */
    #[Field('message_id', required: true)]
    public ?int $messageId = null;

    /**
     * A JSON-serialized object for the new checklist
     */
    #[Field('checklist', required: true)]
    public ?InputChecklist $checklist = null;

    /**
     * A JSON-serialized object for the new inline keyboard for the message
     */
    #[Field('reply_markup', required: false)]
    public ?InlineKeyboardMarkup $replyMarkup = null;

    public function __construct(
        ?string $businessConnectionId = null,
        int|string|null $chatId = null,
        ?int $messageId = null,
        ?InputChecklist $checklist = null,
        ?InlineKeyboardMarkup $replyMarkup = null,
        mixed ...$extra
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($chatId !== null) $this->chatId = $chatId;
        if ($messageId !== null) $this->messageId = $messageId;
        if ($checklist !== null) $this->checklist = $checklist;
        if ($replyMarkup !== null) $this->replyMarkup = $replyMarkup;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
