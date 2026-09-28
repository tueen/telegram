<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Callback button data is invalid or exceeds 64 bytes.
 */
class ButtonDataInvalidException extends BadRequestException
{
    public function __construct(
        string $message = 'Callback button data is invalid or exceeds 64 bytes.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::ButtonDataInvalid);
    }
}
