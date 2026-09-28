<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * The URL provided in an inline button is invalid.
 */
class ButtonUrlInvalidException extends BadRequestException
{
    public function __construct(
        string $message = 'The URL provided in an inline button is invalid.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::ButtonUrlInvalid);
    }
}
