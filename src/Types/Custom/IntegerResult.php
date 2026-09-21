<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types\Custom;

use Tueen\Telegram\Types\Type;

/**
 * Encapsulates integer API responses from Telegram (e.g. getChatMemberCount) into an immutable, rich object.
 */
class IntegerResult extends Type
{
    public private(set) int $value;

    public function __construct(int|array $data = 0)
    {
        if (is_int($data)) {
            $this->value = $data;
            parent::__construct(['value' => $data]);
        } else {
            $this->value = (int)($data['value'] ?? $data['result'] ?? 0);
            parent::__construct($data);
        }
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function toInt(): int
    {
        return $this->value;
    }

    public function isPositive(): bool
    {
        return $this->value > 0;
    }

    public function isZero(): bool
    {
        return $this->value === 0;
    }

    public function toArray(): array
    {
        return ['value' => $this->value];
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }

    public function jsonSerialize(): int
    {
        return $this->value;
    }
}
