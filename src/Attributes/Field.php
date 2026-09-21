<?php

declare(strict_types=1);

namespace Tueen\Telegram\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
readonly class Field
{
    public function __construct(
        public string $name,
        public bool $required = false
    ) {}
}
