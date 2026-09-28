<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Base exception for HTTP 403 Forbidden responses from Telegram Bot API.
 */
class ForbiddenException extends ApiException
{
    public function __construct(
        string $message,
        int $errorCode = 403,
        ?array $parameters = null,
        ?Throwable $previous = null,
        ?TelegramErrorCode $reason = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, $reason ?? TelegramErrorCode::Unknown);
    }
}
