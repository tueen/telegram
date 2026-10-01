<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types\Custom;

use Tueen\Telegram\Types\Type;

/**
 * Encapsulates boolean API responses from Telegram into an immutable, rich object.
 */
class BooleanResult extends Type
{
    private(set) bool $value;

    /**
     * Whether the boolean result is strictly true.
     */
    public bool $isTrue {
        get => $this->value === true;
    }

    /**
     * Whether the boolean result is strictly false.
     */
    public bool $isFalse {
        get => $this->value === false;
    }

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

    public function toBool(): bool
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
        return $this->value ? 'true' : 'false';
    }

    #[\Override]
    public function jsonSerialize(): bool
    {
        return $this->value;
    }
}
