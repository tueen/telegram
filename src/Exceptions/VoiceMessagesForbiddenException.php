<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * The recipient has disabled voice messages in their privacy settings.
 */
class VoiceMessagesForbiddenException extends BadRequestException
{
    public function __construct(
        string $message = 'The recipient has disabled voice messages in their privacy settings.',
        int $errorCode = 400,
        ?array $parameters = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $parameters, $previous, TelegramErrorCode::VoiceMessagesForbidden);
    }
}
