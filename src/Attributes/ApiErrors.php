<?php

declare(strict_types=1);

namespace Tueen\Telegram\Attributes;

use Attribute;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Declares the possible Telegram Bot API errors that can be returned by this method.
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
readonly class ApiErrors
{
    /**
     * @param list<TelegramErrorCode> $errors
     */
    public function __construct(
        public array $errors = []
    ) {}
}
