<?php

declare(strict_types=1);

namespace Tueen\Telegram\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
readonly class RequiresUpload
{
    public function __construct(
        public bool $required = true
    ) {}
}
