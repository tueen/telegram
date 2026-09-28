<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * The user cannot be verified.
 */
class UserCantBeVerifiedException extends BadRequestException
{
    public function __construct(
        string $message = 'The user cannot be verified.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::UserCantBeVerified);
    }
}
