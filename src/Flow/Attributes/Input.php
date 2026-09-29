<?php

declare(strict_types=1);

namespace Tueen\Telegram\Flow\Attributes;

use Attribute;

/**
 * Declares a method as a handler for free-text or update inputs within an InteractiveFlow.
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class Input
{
    public function __construct(
        public readonly ?string $type = null
    ) {}
}
