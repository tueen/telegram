<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Invalid language code; must be 2-letter ISO 639-1.
 */
class InvalidLanguageCodeException extends BadRequestException
{
    public function __construct(
        string $message = 'Invalid language code; must be 2-letter ISO 639-1.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::InvalidLanguageCode);
    }
}
