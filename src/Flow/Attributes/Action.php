<?php

declare(strict_types=1);

namespace Tueen\Telegram\Flow\Attributes;

use Attribute;

/**
 * Declares a method as a handler for an inline keyboard callback action in an InteractiveFlow.
 *
 * Supports literal patterns ('buy') or parameterized patterns ('item_{id}').
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class Action
{
    public function __construct(
        public readonly string $pattern
    ) {}
}
