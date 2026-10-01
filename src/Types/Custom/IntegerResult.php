<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types\Custom;

use Tueen\Telegram\Types\Type;

/**
 * Encapsulates integer API responses from Telegram (e.g. getChatMemberCount) into an immutable, rich object.
 */
class IntegerResult extends Type
{
    private(set) int $value;

    /**
     * Whether the integer value is strictly positive (> 0).
     */
    public bool $isPositive {
        get => $this->value > 0;
    }

    /**
     * Whether the integer value is zero (== 0).
     */
    public bool $isZero {
        get => $this->value === 0;
    }

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

    public function toInt(): int
    {
        return $this->value;
    }

    #[\Override]
    public function toArray(): array
    {
        return ['value' => $this->value];
    }

    #[\Override]
    public function __toString(): string
    {
        return (string)$this->value;
    }

    #[\Override]
    public function jsonSerialize(): int
    {
        return $this->value;
    }
}
