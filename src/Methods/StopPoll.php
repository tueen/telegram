<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\InlineKeyboardMarkup;
use Tueen\Telegram\Types\Poll;

/**
 * Use this method to stop a poll which was sent by the bot. On success, the stopped Poll is returned.
 *
 * @link https://core.telegram.org/bots/api#stoppoll
 */
#[ApiMethod('stopPoll', 'POST')]
#[ReturnType(Poll::class, isArray: false)]
class StopPoll extends Method
{
    /**
     * Unique identifier for the target chat or username of the target bot, supergroup or channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * Identifier of the original message with the poll
     */
    #[Field('message_id', required: true)]
    public ?int $messageId = null;

    /**
     * Unique identifier of the business connection on behalf of which the message to be edited was sent
     */
    #[Field('business_connection_id', required: false)]
    public ?string $businessConnectionId = null;

    /**
     * A JSON-serialized object for a new message inline keyboard
     */
    #[Field('reply_markup', required: false)]
    public ?InlineKeyboardMarkup $replyMarkup = null;

    public function __construct(
        int|string|null $chatId = null,
        ?int $messageId = null,
        ?string $businessConnectionId = null,
        ?InlineKeyboardMarkup $replyMarkup = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($messageId !== null) $this->messageId = $messageId;
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($replyMarkup !== null) $this->replyMarkup = $replyMarkup;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
