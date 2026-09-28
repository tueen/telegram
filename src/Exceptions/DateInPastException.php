<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Scheduled date cannot be in the past.
 */
class DateInPastException extends BadRequestException
{
    public function __construct(
        string $message = 'Scheduled date cannot be in the past.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::DateInPast);
    }
}
