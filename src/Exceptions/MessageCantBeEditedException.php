<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Message cannot be edited.
 */
class MessageCantBeEditedException extends BadRequestException
{
    public function __construct(
        string $message = 'Message cannot be edited.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::MessageCantBeEdited);
    }
}
