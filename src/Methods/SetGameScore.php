<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Message;

/**
 * Use this method to set the score of the specified user in a game message. On success, if the message is not an inline message, the Message is returned, otherwise True is returned. Returns an error, if the new score is not greater than the user's current score in the chat and force is False.
 *
 * @link https://core.telegram.org/bots/api#setgamescore
 */
#[ApiMethod('setGameScore', 'POST')]
#[ReturnType(Message::class, isArray: false)]
class SetGameScore extends Method
{
    /**
     * User identifier
     */
    #[Field('user_id', required: true)]
    public ?int $userId = null;

    /**
     * New score, must be non-negative
     */
    #[Field('score', required: true)]
    public ?int $score = null;

    /**
     * Pass True if the high score is allowed to decrease. This can be useful when fixing mistakes or banning cheaters.
     */
    #[Field('force', required: false)]
    public ?bool $force = null;

    /**
     * Pass True if the game message should not be automatically edited to include the current scoreboard
     */
    #[Field('disable_edit_message', required: false)]
    public ?bool $disableEditMessage = null;

    /**
     * Required if inline_message_id is not specified. Unique identifier for the target chat.
     */
    #[Field('chat_id', required: false)]
    public ?int $chatId = null;

    /**
     * Required if inline_message_id is not specified. Identifier of the sent message.
     */
    #[Field('message_id', required: false)]
    public ?int $messageId = null;

    /**
     * Required if chat_id and message_id are not specified. Identifier of the inline message.
     */
    #[Field('inline_message_id', required: false)]
    public ?string $inlineMessageId = null;

    public function __construct(
        ?int $userId = null,
        ?int $score = null,
        ?bool $force = null,
        ?bool $disableEditMessage = null,
        ?int $chatId = null,
        ?int $messageId = null,
        ?string $inlineMessageId = null,
        mixed ...$extra
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($score !== null) $this->score = $score;
        if ($force !== null) $this->force = $force;
        if ($disableEditMessage !== null) $this->disableEditMessage = $disableEditMessage;
        if ($chatId !== null) $this->chatId = $chatId;
        if ($messageId !== null) $this->messageId = $messageId;
        if ($inlineMessageId !== null) $this->inlineMessageId = $inlineMessageId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
