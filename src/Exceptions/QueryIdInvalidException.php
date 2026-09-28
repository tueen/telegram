<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Query is too old or query_id is invalid.
 */
class QueryIdInvalidException extends BadRequestException
{
    public function __construct(
        string $message = 'Query is too old or query_id is invalid.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::QueryIdInvalid);
    }
}
