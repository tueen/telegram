<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * The specified message was not found.
 */
class MessageNotFoundException extends BadRequestException
{
    public function __construct(
        string $message = 'The specified message was not found.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::MessageNotFound);
    }
}
