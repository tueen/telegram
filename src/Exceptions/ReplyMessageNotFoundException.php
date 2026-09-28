<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * The message specified in reply_parameters was not found.
 */
class ReplyMessageNotFoundException extends BadRequestException
{
    public function __construct(
        string $message = 'The message specified in reply_parameters was not found.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::ReplyMessageNotFound);
    }
}
