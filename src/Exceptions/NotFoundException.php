<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Base exception for HTTP 404 Not Found responses from Telegram Bot API.
 */
class NotFoundException extends ApiException
{
    public function __construct(
        string $message = 'Not Found: method not found',
        int $errorCode = 404,
        ?array $parameters = null,
        ?Throwable $previous = null,
        ?TelegramErrorCode $reason = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, $reason ?? TelegramErrorCode::Unknown);
    }
}
