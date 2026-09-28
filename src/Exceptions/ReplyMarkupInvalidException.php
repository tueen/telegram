<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * The reply_markup JSON structure is invalid.
 */
class ReplyMarkupInvalidException extends BadRequestException
{
    public function __construct(
        string $message = 'The reply_markup JSON structure is invalid.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::ReplyMarkupInvalid);
    }
}
