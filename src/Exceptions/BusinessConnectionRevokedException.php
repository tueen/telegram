<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * The business connection was revoked by the user.
 */
class BusinessConnectionRevokedException extends ForbiddenException
{
    public function __construct(
        string $message = 'The business connection was revoked by the user.',
        int $errorCode = 403,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::BusinessConnectionRevoked);
    }
}
