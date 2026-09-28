<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * The specified user was not found.
 */
class UserNotFoundException extends BadRequestException
{
    public function __construct(
        string $message = 'The specified user was not found.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::UserNotFound);
    }
}
