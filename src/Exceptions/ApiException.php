<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;

class ApiException extends TelegramException
{
    public function __construct(
        string $message,
        public readonly int $errorCode = 0,
        public readonly ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $previous);
    }

    public static function fromResponse(array $response): self
    {
        $errorCode = (int)($response['error_code'] ?? 0);
        $description = (string)($response['description'] ?? 'Unknown Telegram API Error');
        $parameters = $response['parameters'] ?? null;

        if ($errorCode === 429 || isset($parameters['retry_after'])) {
            $retryAfter = (int)($parameters['retry_after'] ?? 1);
            return new RateLimitException($description, $errorCode, $retryAfter, $parameters);
        }

        return new self($description, $errorCode, $parameters);
    }
}
