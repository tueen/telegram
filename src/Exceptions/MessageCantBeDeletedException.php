<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Message cannot be deleted (e.g. 48-hour limit expired or no rights).
 */
class MessageCantBeDeletedException extends BadRequestException
{
    public function __construct(
        string $message = 'Message cannot be deleted (e.g. 48-hour limit expired or no rights).',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::MessageCantBeDeleted);
    }
}
