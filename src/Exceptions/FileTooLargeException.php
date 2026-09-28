<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Thrown when an uploaded file exceeds the HTTP 413 file size limit.
 */
class FileTooLargeException extends ApiException
{
    public function __construct(
        string $message = 'Request Entity Too Large: uploaded file exceeds size limit',
        int $errorCode = 413,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::FileTooLarge);
    }
}
