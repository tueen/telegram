<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Message text exceeds the maximum character limit.
 */
class MessageTooLongException extends BadRequestException
{
    public function __construct(
        string $message = 'Message text exceeds the maximum character limit.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::MessageTooLong);
    }
}
