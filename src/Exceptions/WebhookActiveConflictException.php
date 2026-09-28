<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Can't use getUpdates while a webhook is active.
 */
class WebhookActiveConflictException extends ConflictException
{
    public function __construct(
        string $message = 'Can\'t use getUpdates while a webhook is active.',
        int $errorCode = 409,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::ConflictWebhookActive);
    }
}
