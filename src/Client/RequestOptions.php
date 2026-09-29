<?php

declare(strict_types=1);

namespace Tueen\Telegram\Client;

use Closure;

class RequestOptions
{
    public ?Closure $uploadProgress = null;
    public ?Closure $downloadProgress = null;
    public ?float $timeout = null;
    public ?float $connectTimeout = null;
    public array $headers = [];
    public array $custom = [];

    public function __construct(array $options = [])
    {
        if (!empty($options)) {
            $this->applyArray($options);
        }
    }

    public static function make(array $options = []): static
    {
        return new static($options);
    }

    public static function create(array $options = []): static
    {
        return new static($options);
    }

    public static function from(self|array|null $options): ?static
    {
        if ($options === null) {
            return null;
        }
        if ($options instanceof static) {
            return $options;
        }
        if ($options instanceof self) {
            $new = new static();
            $new->uploadProgress = $options->uploadProgress;
            $new->downloadProgress = $options->downloadProgress;
            $new->timeout = $options->timeout;
            $new->connectTimeout = $options->connectTimeout;
            $new->headers = $options->headers;
            $new->custom = $options->custom;
            return $new;
        }
        return new static($options);
    }

    public function applyArray(array $options): static
    {
        if (isset($options['upload_progress']) && is_callable($options['upload_progress'])) {
            $this->uploadProgress = $options['upload_progress'] instanceof Closure ? $options['upload_progress'] : Closure::fromCallable($options['upload_progress']);
        } elseif (isset($options['onUploadProgress']) && is_callable($options['onUploadProgress'])) {
            $this->uploadProgress = $options['onUploadProgress'] instanceof Closure ? $options['onUploadProgress'] : Closure::fromCallable($options['onUploadProgress']);
        }

        if (isset($options['download_progress']) && is_callable($options['download_progress'])) {
            $this->downloadProgress = $options['download_progress'] instanceof Closure ? $options['download_progress'] : Closure::fromCallable($options['download_progress']);
        } elseif (isset($options['onDownloadProgress']) && is_callable($options['onDownloadProgress'])) {
            $this->downloadProgress = $options['onDownloadProgress'] instanceof Closure ? $options['onDownloadProgress'] : Closure::fromCallable($options['onDownloadProgress']);
        }

        if (isset($options['timeout']) && (is_float($options['timeout']) || is_int($options['timeout']))) {
            $this->timeout = (float)$options['timeout'];
        }

        if (isset($options['connect_timeout']) && (is_float($options['connect_timeout']) || is_int($options['connect_timeout']))) {
            $this->connectTimeout = (float)$options['connect_timeout'];
        }

        if (isset($options['headers']) && is_array($options['headers'])) {
            $this->headers = array_merge($this->headers, $options['headers']);
        }

        if (isset($options['custom']) && is_array($options['custom'])) {
            $this->custom = array_merge($this->custom, $options['custom']);
        }

        return $this;
    }

    public function onUploadProgress(?callable $callback): static
    {
        $this->uploadProgress = $callback !== null ? ($callback instanceof Closure ? $callback : Closure::fromCallable($callback)) : null;
        return $this;
    }

    public function withUploadProgress(?callable $callback): static
    {
        return $this->onUploadProgress($callback);
    }

    public function onDownloadProgress(?callable $callback): static
    {
        $this->downloadProgress = $callback !== null ? ($callback instanceof Closure ? $callback : Closure::fromCallable($callback)) : null;
        return $this;
    }

    public function withDownloadProgress(?callable $callback): static
    {
        return $this->onDownloadProgress($callback);
    }

    public function timeout(?float $seconds): static
    {
        $this->timeout = $seconds;
        return $this;
    }

    public function withTimeout(?float $seconds): static
    {
        return $this->timeout($seconds);
    }

    public function connectTimeout(?float $seconds): static
    {
        $this->connectTimeout = $seconds;
        return $this;
    }

    public function headers(array $headers): static
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    public function header(string $name, string $value): static
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function set(string $key, mixed $value): static
    {
        $this->custom[$key] = $value;
        return $this;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->custom[$key] ?? $default;
    }
}
