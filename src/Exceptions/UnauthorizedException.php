<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Base exception for HTTP 401 Unauthorized responses from Telegram Bot API.
 */
class UnauthorizedException extends ApiException
{
    public function __construct(
        string $message = 'Unauthorized: invalid token specified',
        int $errorCode = 401,
        ?array $parameters = null,
        ?Throwable $previous = null,
        ?TelegramErrorCode $reason = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, $reason ?? TelegramErrorCode::Unauthorized);
    }
}
