<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Sticker dimensions must be exactly 512x512 pixels.
 */
class StickerDimensionsInvalidException extends BadRequestException
{
    public function __construct(
        string $message = 'Sticker dimensions must be exactly 512x512 pixels.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::StickerDimensionsInvalid);
    }
}
