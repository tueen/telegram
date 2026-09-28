<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * The forum topic was deleted.
 */
class TopicDeletedException extends BadRequestException
{
    public function __construct(
        string $message = 'The forum topic was deleted.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::TopicDeleted);
    }
}
