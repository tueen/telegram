<?php

declare(strict_types=1);

namespace Tueen\Telegram\Client;

use Tueen\Telegram\Types\Custom\InputFile;

class Request
{
    /**
     * @param string $endpoint The Telegram API method name (e.g. sendMessage)
     * @param array $parameters Scalar / JSON parameters
     * @param array<string, InputFile> $files Multipart uploaded files
     * @param string $httpMethod HTTP verb (POST or GET)
     */
    public function __construct(
        public readonly string $endpoint,
        public readonly array $parameters = [],
        public readonly array $files = [],
        public readonly string $httpMethod = 'POST',
        public readonly ?\Closure $uploadProgress = null,
        public readonly ?\Closure $downloadProgress = null,
        public readonly ?float $timeout = null,
        public readonly ?float $connectTimeout = null
    ) {}

    public array $params {
        get => $this->parameters;
    }

    public string $method {
        get => $this->endpoint;
    }

    public function isMultipart(): bool
    {
        return !empty($this->files);
    }

    private function copyWith(array $overrides): self
    {
        return new self(
            endpoint: $overrides['endpoint'] ?? $this->endpoint,
            parameters: $overrides['parameters'] ?? $this->parameters,
            files: $overrides['files'] ?? $this->files,
            httpMethod: $overrides['httpMethod'] ?? $this->httpMethod,
            uploadProgress: array_key_exists('uploadProgress', $overrides) ? $overrides['uploadProgress'] : $this->uploadProgress,
            downloadProgress: array_key_exists('downloadProgress', $overrides) ? $overrides['downloadProgress'] : $this->downloadProgress,
            timeout: array_key_exists('timeout', $overrides) ? $overrides['timeout'] : $this->timeout,
            connectTimeout: array_key_exists('connectTimeout', $overrides) ? $overrides['connectTimeout'] : $this->connectTimeout,
        );
    }

    #[\NoDiscard]
    public function withEndpoint(string $endpoint): self
    {
        return $this->copyWith(['endpoint' => $endpoint]);
    }

    #[\NoDiscard]
    public function withParameter(string $name, mixed $value): self
    {
        return $this->copyWith(['parameters' => [...$this->parameters, $name => $value]]);
    }

    #[\NoDiscard]
    public function withParameters(array $parameters): self
    {
        return $this->copyWith(['parameters' => $parameters]);
    }

    #[\NoDiscard]
    public function withFile(string $name, InputFile $file): self
    {
        return $this->copyWith(['files' => [...$this->files, $name => $file]]);
    }

    #[\NoDiscard]
    public function withHttpMethod(string $httpMethod): self
    {
        return $this->copyWith(['httpMethod' => $httpMethod]);
    }

    #[\NoDiscard]
    public function withTimeout(?float $timeout): self
    {
        return $this->copyWith(['timeout' => $timeout]);
    }

    #[\NoDiscard]
    public function withConnectTimeout(?float $connectTimeout): self
    {
        return $this->copyWith(['connectTimeout' => $connectTimeout]);
    }
}
