<?php

declare(strict_types=1);

namespace Tueen\Telegram;

use Closure;
use Psr\Log\LoggerInterface;
use Tueen\Telegram\Client\HttpClientInterface;

class ConfigBuilder
{
    private string $botToken = '';
    private string $apiServer = 'https://api.telegram.org';
    private float $timeout = 30.0;
    private float $connectTimeout = 10.0;
    private ?string $proxy = null;
    private ?HttpClientInterface $httpClient = null;
    private ?LoggerInterface $logger = null;
    private ?Closure $uploadProgress = null;
    private ?Closure $downloadProgress = null;
    private int $retryCount = 3;
    private bool $testEnvironment = false;

    public function __construct(string $botToken = '')
    {
        $this->botToken = $botToken;
    }

    public function withToken(string $botToken): static
    {
        $this->botToken = $botToken;
        return $this;
    }

    public function withApiServer(string $apiServer): static
    {
        $this->apiServer = $apiServer;
        return $this;
    }

    public function withTimeout(float $timeout): static
    {
        $this->timeout = $timeout;
        return $this;
    }

    public function withConnectTimeout(float $connectTimeout): static
    {
        $this->connectTimeout = $connectTimeout;
        return $this;
    }

    public function withProxy(?string $proxy): static
    {
        $this->proxy = $proxy;
        return $this;
    }

    public function withHttpClient(?HttpClientInterface $httpClient): static
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    public function withLogger(?LoggerInterface $logger): static
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @param Closure(int $bytesUploaded, int $totalBytes, float $percentage): void $callback
     */
    public function withUploadProgress(?Closure $callback): static
    {
        $this->uploadProgress = $callback;
        return $this;
    }

    /**
     * @param Closure(int $bytesDownloaded, int $totalBytes, float $percentage): void $callback
     */
    public function withDownloadProgress(?Closure $callback): static
    {
        $this->downloadProgress = $callback;
        return $this;
    }

    public function withRetryCount(int $count): static
    {
        $this->retryCount = $count;
        return $this;
    }

    public function withTestEnvironment(bool $enabled = true): static
    {
        $this->testEnvironment = $enabled;
        return $this;
    }

    public function build(): Config
    {
        return new Config(
            botToken: $this->botToken,
            apiServer: $this->apiServer,
            timeout: $this->timeout,
            connectTimeout: $this->connectTimeout,
            proxy: $this->proxy,
            httpClient: $this->httpClient,
            logger: $this->logger,
            uploadProgress: $this->uploadProgress,
            downloadProgress: $this->downloadProgress,
            retryCount: $this->retryCount,
            testEnvironment: $this->testEnvironment
        );
    }
}
