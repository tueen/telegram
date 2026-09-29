<?php

declare(strict_types=1);

namespace Tueen\Telegram\Flow\Attributes;

use Attribute;

/**
 * Defines a human-readable title for breadcrumbs navigation on an InteractiveFlow class.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class Breadcrumb
{
    public function __construct(
        public readonly string $title
    ) {}
}
