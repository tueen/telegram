<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * The file format or MIME type is not accepted for this method.
 */
class WrongFileTypeException extends BadRequestException
{
    public function __construct(
        string $message = 'The file format or MIME type is not accepted for this method.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::WrongFileType);
    }
}
