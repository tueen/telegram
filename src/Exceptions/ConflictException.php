<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Base exception for HTTP 409 Conflict responses from Telegram Bot API.
 */
class ConflictException extends ApiException
{
    public function __construct(
        string $message,
        int $errorCode = 409,
        ?array $parameters = null,
        ?Throwable $previous = null,
        ?TelegramErrorCode $reason = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, $reason ?? TelegramErrorCode::Unknown);
    }
}
