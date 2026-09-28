<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Invalid amount of Telegram Stars (must be between 1 and 25000).
 */
class StarsAmountInvalidException extends BadRequestException
{
    public function __construct(
        string $message = 'Invalid amount of Telegram Stars (must be between 1 and 25000).',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::StarsAmountInvalid);
    }
}
