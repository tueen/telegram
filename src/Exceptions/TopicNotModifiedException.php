<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Topic name or icon is unchanged.
 */
class TopicNotModifiedException extends BadRequestException
{
    public function __construct(
        string $message = 'Topic name or icon is unchanged.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::TopicNotModified);
    }
}
