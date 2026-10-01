<?php

declare(strict_types=1);

namespace Tueen\Telegram\Routing\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class OnCommand
{
    public function __construct(
        public readonly string $command,
        public readonly ?string $description = null,
        public readonly bool $priority = false
    ) {}
}
