<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\BotBlockedException;
use Tueen\Telegram\Exceptions\ChatNotFoundException;
use Tueen\Telegram\Exceptions\MessageCantBeEditedException;
use Tueen\Telegram\Exceptions\MessageNotFoundException;
use Tueen\Telegram\Exceptions\MessageNotModifiedException;
use Tueen\Telegram\Exceptions\NotEnoughRightsException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\InlineKeyboardMarkup;
use Tueen\Telegram\Types\Message;

/**
 * Use this method to edit only the reply markup of messages. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned. Note that business messages that were not sent by the bot and do not contain an inline keyboard can only be edited within 48 hours from the time they were sent.
 *
 * @link https://core.telegram.org/bots/api#editmessagereplymarkup
 *
 * @throws MessageNotModifiedException
 * @throws MessageCantBeEditedException
 * @throws ChatNotFoundException
 * @throws MessageNotFoundException
 * @throws BotBlockedException
 * @throws NotEnoughRightsException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('editMessageReplyMarkup', 'POST')]
#[ReturnType(Message::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::MessageNotModified, TelegramErrorCode::MessageCantBeEdited, TelegramErrorCode::ChatNotFound, TelegramErrorCode::MessageNotFound, TelegramErrorCode::BotBlocked, TelegramErrorCode::NotEnoughRights, TelegramErrorCode::FloodWait])]
class EditMessageReplyMarkup extends Method
{
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
     * A JSON-serialized object for an inline keyboard
     */
    #[Field('reply_markup', required: false)]
    public ?InlineKeyboardMarkup $replyMarkup = null;

    public function __construct(
        ?string $businessConnectionId = null,
        int|string|null $chatId = null,
        ?int $messageId = null,
        ?string $inlineMessageId = null,
        ?InlineKeyboardMarkup $replyMarkup = null,
        mixed ...$extra
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($chatId !== null) $this->chatId = $chatId;
        if ($messageId !== null) $this->messageId = $messageId;
        if ($inlineMessageId !== null) $this->inlineMessageId = $inlineMessageId;
        if ($replyMarkup !== null) $this->replyMarkup = $replyMarkup;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
