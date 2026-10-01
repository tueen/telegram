<?php

declare(strict_types=1);

namespace Tueen\Telegram\Client;

class Response
{
    public bool $ok {
        get => ($this->data['ok'] ?? false) === true;
    }

    public bool $isOk {
        get => $this->ok;
    }

    public mixed $result {
        get => $this->data['result'] ?? null;
    }

    public ?string $description {
        get => $this->data['description'] ?? null;
    }

    public ?int $errorCode {
        get => isset($this->data['error_code']) ? (int)$this->data['error_code'] : null;
    }

    public ?array $parameters {
        get => $this->data['parameters'] ?? null;
    }

    public function __construct(
        public readonly int $statusCode,
        public readonly array $data = [],
        public readonly string $rawBody = '',
        public readonly array $headers = []
    ) {}
}
