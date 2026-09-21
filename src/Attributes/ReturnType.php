<?php

declare(strict_types=1);

namespace Tueen\Telegram\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class ReturnType
{
    public function __construct(
        public string $type,
        public bool $isArray = false,
        public int $arrayDepth = 1
    ) {}
}
