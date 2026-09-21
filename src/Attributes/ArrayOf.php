<?php

declare(strict_types=1);

namespace Tueen\Telegram\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
readonly class ArrayOf
{
    public function __construct(
        public string $type,
        public int $depth = 1
    ) {}
}
