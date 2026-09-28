<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Types\InlineKeyboardMarkup;

/**
 * Use this method to edit only the reply markup of an ephemeral message. Note that it is not guaranteed that the user will receive the message edit event, especially if they are offline. On success, True is returned.
 *
 * @link https://core.telegram.org/bots/api#editephemeralmessagereplymarkup
 */
#[ApiMethod('editEphemeralMessageReplyMarkup', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class EditEphemeralMessageReplyMarkup extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * Identifier of the user who received the message
     */
    #[Field('receiver_user_id', required: true)]
    public int $receiverUserId;

    /**
     * Identifier of the ephemeral message to edit
     */
    #[Field('ephemeral_message_id', required: true)]
    public int $ephemeralMessageId;

    /**
     * A JSON-serialized object for an inline keyboard
     */
    #[Field('reply_markup', required: false)]
    public ?InlineKeyboardMarkup $replyMarkup = null;

    public function __construct(
        int|string $chatId,
        int $receiverUserId,
        int $ephemeralMessageId,
        ?InlineKeyboardMarkup $replyMarkup = null,
        mixed ...$extra
    )
    {
        $this->chatId = $chatId;
        $this->receiverUserId = $receiverUserId;
        $this->ephemeralMessageId = $ephemeralMessageId;
        if ($replyMarkup !== null) $this->replyMarkup = $replyMarkup;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
