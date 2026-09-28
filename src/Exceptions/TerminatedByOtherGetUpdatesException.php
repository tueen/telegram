<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Terminated by another getUpdates request (multiple bot instances running).
 */
class TerminatedByOtherGetUpdatesException extends ConflictException
{
    public function __construct(
        string $message = 'Terminated by another getUpdates request (multiple bot instances running).',
        int $errorCode = 409,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::ConflictTerminatedByOtherGetUpdates);
    }
}
