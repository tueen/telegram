<?php

declare(strict_types=1);

namespace Tueen\Telegram\Routing\Attributes;

use Attribute;
use Tueen\Telegram\Enums\UpdateType;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class OnUpdate
{
    public function __construct(
        public readonly UpdateType|string $type,
        public readonly bool $priority = false
    ) {}
}
