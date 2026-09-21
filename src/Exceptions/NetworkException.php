<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;

class NetworkException extends TelegramException
{
    public function __construct(string $message, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
