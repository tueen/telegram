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

    /**
     * Character length of the string.
     */
    public int $length {
        get => mb_strlen($this->value);
    }

    /**
     * Whether the string is empty.
     */
    public bool $isEmpty {
        get => $this->value === '';
    }

    /**
     * Whether the string is not empty.
     */
    public bool $isNotEmpty {
        get => $this->value !== '';
    }

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

    public function toString(): string
    {
        return $this->value;
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

    #[\Override]
    public function toArray(): array
    {
        return ['value' => $this->value];
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->value;
    }

    #[\Override]
    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
