<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Exceeded the limit of 100 commands.
 */
class TooManyCommandsException extends BadRequestException
{
    public function __construct(
        string $message = 'Exceeded the limit of 100 commands.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::TooManyCommands);
    }
}
