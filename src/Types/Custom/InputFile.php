<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types\Custom;

use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;

class InputFile
{
    private mixed $contents;
    private string $filename;
    private ?string $contentType;

    public function __construct(
        mixed $contents,
        ?string $filename = null,
        ?string $contentType = null
    ) {
        $this->contents = $contents;
        $this->filename = $filename ?? 'file.dat';
        $this->contentType = $contentType;
    }

    public static function fromPath(string $filePath, ?string $filename = null, ?string $contentType = null): self
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new InvalidArgumentException("File does not exist or is not readable: {$filePath}");
        }

        $filename ??= basename($filePath);
        $handle = fopen($filePath, 'rb');
        if ($handle === false) {
            throw new InvalidArgumentException("Failed to open file: {$filePath}");
        }

        return new self($handle, $filename, $contentType);
    }

    public static function fromResource($resource, string $filename = 'file.dat', ?string $contentType = null): self
    {
        if (!is_resource($resource)) {
            throw new InvalidArgumentException("Argument must be a valid resource stream.");
        }

        return new self($resource, $filename, $contentType);
    }

    public static function fromString(string $data, string $filename = 'file.dat', ?string $contentType = null): self
    {
        return new self($data, $filename, $contentType);
    }

    public static function fromStream(StreamInterface $stream, string $filename = 'file.dat', ?string $contentType = null): self
    {
        return new self($stream, $filename, $contentType);
    }

    public function getContents(): mixed
    {
        return $this->contents;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    /**
     * Converts to multipart form array entry for Guzzle.
     */
    public function toMultipart(string $name): array
    {
        $part = [
            'name' => $name,
            'contents' => $this->contents,
            'filename' => $this->filename,
        ];

        if ($this->contentType !== null) {
            $part['headers'] = ['Content-Type' => $this->contentType];
        }

        return $part;
    }
}
