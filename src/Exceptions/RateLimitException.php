<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;

class RateLimitException extends ApiException
{
    public function __construct(
        string $message,
        int $errorCode = 429,
        public readonly int $retryAfter = 1,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous);
    }
}
