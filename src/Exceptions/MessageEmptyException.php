<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Message text or content is empty.
 */
class MessageEmptyException extends BadRequestException
{
    public function __construct(
        string $message = 'Message text or content is empty.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::MessageEmpty);
    }
}
