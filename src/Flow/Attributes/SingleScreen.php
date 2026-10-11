<?php

declare(strict_types=1);

namespace Tueen\Telegram\Flow\Attributes;

use Attribute;

/**
 * Marks an InteractiveFlow as a Single-Screen flow, optionally cleaning up
 * any previously active screen message when the flow starts.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class SingleScreen
{
    public function __construct(
        public readonly bool $clearPrevious = true
    ) {}
}
