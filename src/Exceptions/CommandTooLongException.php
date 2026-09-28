<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Command name exceeds 32 chars or description exceeds 256 chars.
 */
class CommandTooLongException extends BadRequestException
{
    public function __construct(
        string $message = 'Command name exceeds 32 chars or description exceeds 256 chars.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::CommandTooLong);
    }
}
