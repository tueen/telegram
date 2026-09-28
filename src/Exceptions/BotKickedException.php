<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * The bot is not a member of the chat or was kicked.
 */
class BotKickedException extends ForbiddenException
{
    public function __construct(
        string $message = 'The bot is not a member of the chat or was kicked.',
        int $errorCode = 403,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::BotKicked);
    }
}
