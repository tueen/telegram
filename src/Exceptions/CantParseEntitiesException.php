<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Failed to parse text formatting entities with current parse_mode.
 */
class CantParseEntitiesException extends BadRequestException
{
    public function __construct(
        string $message = 'Failed to parse text formatting entities with current parse_mode.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::CantParseEntities);
    }
}
