<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\BotKickedException;
use Tueen\Telegram\Exceptions\ChatNotFoundException;
use Tueen\Telegram\Exceptions\MessageNotFoundException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\GameHighScore;

/**
 * Use this method to get data for high score tables. Will return the score of the specified user and several of their neighbors in a game. Returns an Array of GameHighScore objects.
 *
 * @link https://core.telegram.org/bots/api#getgamehighscores
 *
 * @throws ChatNotFoundException
 * @throws MessageNotFoundException
 * @throws BotKickedException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('getGameHighScores', 'POST')]
#[ReturnType(GameHighScore::class, isArray: true)]
#[ApiErrors([TelegramErrorCode::ChatNotFound, TelegramErrorCode::MessageNotFound, TelegramErrorCode::BotKicked, TelegramErrorCode::FloodWait])]
class GetGameHighScores extends Method
{
    /**
     * Target user id
     */
    #[Field('user_id', required: true)]
    public ?int $userId = null;

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
        ?int $chatId = null,
        ?int $messageId = null,
        ?string $inlineMessageId = null,
        mixed ...$extra
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($chatId !== null) $this->chatId = $chatId;
        if ($messageId !== null) $this->messageId = $messageId;
        if ($inlineMessageId !== null) $this->inlineMessageId = $inlineMessageId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
