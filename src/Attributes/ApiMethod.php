<?php

declare(strict_types=1);

namespace Tueen\Telegram\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class ApiMethod
{
    public function __construct(
        public string $name,
        public string $httpMethod = 'POST'
    ) {}
}
