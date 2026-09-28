<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * The bot lacks required administrative rights or permissions.
 */
class NotEnoughRightsException extends ForbiddenException
{
    public function __construct(
        string $message = 'The bot lacks required administrative rights or permissions.',
        int $errorCode = 403,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::NotEnoughRights);
    }
}
