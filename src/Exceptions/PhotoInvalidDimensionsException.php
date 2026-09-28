<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Photo dimensions exceed or violate allowed aspect ratio.
 */
class PhotoInvalidDimensionsException extends BadRequestException
{
    public function __construct(
        string $message = 'Photo dimensions exceed or violate allowed aspect ratio.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::PhotoInvalidDimensions);
    }
}
