<?php

declare(strict_types=1);

namespace Tueen\Telegram\Client;

class Response
{
    public function __construct(
        public readonly int $statusCode,
        public readonly array $data = [],
        public readonly string $rawBody = '',
        public readonly array $headers = []
    ) {}

    public function isOk(): bool
    {
        return ($this->data['ok'] ?? false) === true;
    }

    public function getResult(): mixed
    {
        return $this->data['result'] ?? null;
    }

    public function getDescription(): ?string
    {
        return $this->data['description'] ?? null;
    }

    public function getErrorCode(): ?int
    {
        return isset($this->data['error_code']) ? (int)$this->data['error_code'] : null;
    }

    public function getParameters(): ?array
    {
        return $this->data['parameters'] ?? null;
    }
}
