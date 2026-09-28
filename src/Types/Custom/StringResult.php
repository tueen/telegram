<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types\Custom;

use Tueen\Telegram\Types\Type;

/**
 * Encapsulates string API responses from Telegram (e.g. exportChatInviteLink, createInvoiceLink) into an immutable, rich object.
 */
class StringResult extends Type
{
    private(set) string $value;

    public function __construct(string|array $data = '')
    {
        if (is_string($data)) {
            $this->value = $data;
            parent::__construct(['value' => $data]);
        } else {
            $this->value = (string)($data['value'] ?? $data['result'] ?? '');
            parent::__construct($data);
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function length(): int
    {
        return mb_strlen($this->value);
    }

    public function isEmpty(): bool
    {
        return $this->value === '';
    }

    public function isNotEmpty(): bool
    {
        return $this->value !== '';
    }

    public function contains(string $needle): bool
    {
        return str_contains($this->value, $needle);
    }

    public function startsWith(string $prefix): bool
    {
        return str_starts_with($this->value, $prefix);
    }

    public function endsWith(string $suffix): bool
    {
        return str_ends_with($this->value, $suffix);
    }

    public function toArray(): array
    {
        return ['value' => $this->value];
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
