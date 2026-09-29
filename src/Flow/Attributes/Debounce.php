<?php

declare(strict_types=1);

namespace Tueen\Telegram\Flow\Attributes;

use Attribute;

/**
 * Prevents rapid double-clicks on button actions by rate-limiting calls within a time window.
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class Debounce
{
    public function __construct(
        public readonly float $seconds = 1.0,
        public readonly ?string $notice = '⚠️ Please wait a moment...',
        public readonly bool $showAlert = false,
    ) {}
}
