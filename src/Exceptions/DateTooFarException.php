<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Scheduled date is too far in the future (max 30 days).
 */
class DateTooFarException extends BadRequestException
{
    public function __construct(
        string $message = 'Scheduled date is too far in the future (max 30 days).',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::DateTooFar);
    }
}
