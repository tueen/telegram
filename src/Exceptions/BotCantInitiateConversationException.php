<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Bot can't initiate conversation with a user who hasn't started the bot.
 */
class BotCantInitiateConversationException extends ForbiddenException
{
    public function __construct(
        string $message = 'Bot can\'t initiate conversation with a user who hasn\'t started the bot.',
        int $errorCode = 403,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::BotCantInitiateConversation);
    }
}
