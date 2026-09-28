<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * The media container or media array is empty.
 */
class MediaEmptyException extends BadRequestException
{
    public function __construct(
        string $message = 'The media container or media array is empty.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::MediaEmpty);
    }
}
