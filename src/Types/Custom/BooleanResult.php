<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types\Custom;

use Tueen\Telegram\Types\Type;

/**
 * Encapsulates boolean API responses from Telegram into an immutable, rich object.
 */
class BooleanResult extends Type
{
    public private(set) bool $value;

    public function __construct(bool|array $data = true)
    {
        if (is_bool($data)) {
            $this->value = $data;
            parent::__construct(['value' => $data]);
        } else {
            $this->value = (bool)($data['value'] ?? $data['result'] ?? true);
            parent::__construct($data);
        }
    }

    public function isTrue(): bool
    {
        return $this->value === true;
    }

    public function isFalse(): bool
    {
        return $this->value === false;
    }

    public function getValue(): bool
    {
        return $this->value;
    }

    public function toBool(): bool
    {
        return $this->value;
    }

    public function toArray(): array
    {
        return ['value' => $this->value];
    }

    public function __toString(): string
    {
        return $this->value ? 'true' : 'false';
    }

    public function jsonSerialize(): bool
    {
        return $this->value;
    }
}
