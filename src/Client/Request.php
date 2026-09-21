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
        public readonly ?\Closure $downloadProgress = null
    ) {}

    public function isMultipart(): bool
    {
        return !empty($this->files);
    }
}
