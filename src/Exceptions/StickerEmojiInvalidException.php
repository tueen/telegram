<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * The emoji provided for the sticker is invalid.
 */
class StickerEmojiInvalidException extends BadRequestException
{
    public function __construct(
        string $message = 'The emoji provided for the sticker is invalid.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::StickerEmojiInvalid);
    }
}
