<?php

declare(strict_types=1);

namespace Tueen\Telegram\Enums;

/**
 * Defines how Telegram API errors and client errors are handled by the client.
 */
enum ErrorHandlingMode: string
{
    /**
     * Throw typed exceptions (e.g. ApiException, RateLimitException, NetworkException).
     * This is the default mode.
     */
    case EXCEPTION = 'exception';

    /**
     * Return a Tueen\Telegram\Types\Error object instead of throwing exceptions.
     */
    case ERROR_OBJECT = 'error_object';
}
