<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Base exception for all Telegram Bot API response errors.
 */
class ApiException extends TelegramException
{
    public readonly TelegramErrorCode $reason;

    public function __construct(
        string $message,
        public readonly int $errorCode = 0,
        public readonly ?array $parameters = null,
        ?Throwable $previous = null,
        ?TelegramErrorCode $reason = null
    ) {
        $this->reason = $reason ?? ErrorMatcher::matchCode($errorCode, $message);
        parent::__construct($message, $errorCode, $previous);
    }

    /**
     * Creates the most specific typed exception subclass from Telegram response data.
     */
    public static function fromResponse(array $response, ?Throwable $previous = null): self
    {
        $errorCode = (int)($response['error_code'] ?? 0);
        $description = (string)($response['description'] ?? 'Unknown Telegram API Error');
        $parameters = $response['parameters'] ?? null;

        return ErrorMatcher::createException($description, $errorCode, $parameters, $previous);
    }

    /**
     * Checks if this exception matches the specified Telegram error code.
     */
    public function is(TelegramErrorCode $code): bool
    {
        return $this->reason === $code;
    }

    /**
     * Helper check for ChatNotFound error.
     */
    public function isChatNotFound(): bool
    {
        return $this->reason === TelegramErrorCode::ChatNotFound;
    }

    /**
     * Helper check for BotBlocked error.
     */
    public function isBotBlocked(): bool
    {
        return $this->reason === TelegramErrorCode::BotBlocked;
    }

    /**
     * Helper check for RateLimit / FloodWait error.
     */
    public function isRateLimit(): bool
    {
        return $this->reason === TelegramErrorCode::FloodWait;
    }
}
