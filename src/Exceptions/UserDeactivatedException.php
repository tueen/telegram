<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * The target user account was deleted or deactivated.
 */
class UserDeactivatedException extends ForbiddenException
{
    public function __construct(
        string $message = 'The target user account was deleted or deactivated.',
        int $errorCode = 403,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::UserDeactivated);
    }
}
