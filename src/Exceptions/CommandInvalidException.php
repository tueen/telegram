<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Command names must start with a letter and contain only a-z, 0-9, and _.
 */
class CommandInvalidException extends BadRequestException
{
    public function __construct(
        string $message = 'Command names must start with a letter and contain only a-z, 0-9, and _.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::CommandInvalid);
    }
}
