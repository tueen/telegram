<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * The specified chat_id was not found or is inaccessible.
 */
class ChatNotFoundException extends BadRequestException
{
    public function __construct(
        string $message = 'The specified chat_id was not found or is inaccessible.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::ChatNotFound);
    }
}
