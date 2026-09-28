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
        final public readonly string $endpoint,
        final public readonly array $parameters = [],
        final public readonly array $files = [],
        final public readonly string $httpMethod = 'POST',
        final public readonly ?\Closure $uploadProgress = null,
        final public readonly ?\Closure $downloadProgress = null
    ) {}

    public function isMultipart(): bool
    {
        return !empty($this->files);
    }

    #[\NoDiscard]
    public function withEndpoint(string $endpoint): self
    {
        return clone($this, ['endpoint' => $endpoint]);
    }

    #[\NoDiscard]
    public function withParameter(string $name, mixed $value): self
    {
        return clone($this, ['parameters' => [...$this->parameters, $name => $value]]);
    }

    #[\NoDiscard]
    public function withParameters(array $parameters): self
    {
        return clone($this, ['parameters' => $parameters]);
    }

    #[\NoDiscard]
    public function withFile(string $name, InputFile $file): self
    {
        return clone($this, ['files' => [...$this->files, $name => $file]]);
    }

    #[\NoDiscard]
    public function withHttpMethod(string $httpMethod): self
    {
        return clone($this, ['httpMethod' => $httpMethod]);
    }
}
