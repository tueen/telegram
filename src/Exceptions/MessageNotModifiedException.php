<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Message content and reply markup are identical to current content.
 */
class MessageNotModifiedException extends BadRequestException
{
    public function __construct(
        string $message = 'Message content and reply markup are identical to current content.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::MessageNotModified);
    }
}
