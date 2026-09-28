<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Passing empty commands array; use deleteMyCommands instead.
 */
class CommandsListEmptyException extends BadRequestException
{
    public function __construct(
        string $message = 'Passing empty commands array; use deleteMyCommands instead.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::CommandsListEmpty);
    }
}
