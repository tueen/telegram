<?php

declare(strict_types=1);

namespace Tueen\Telegram;

use Closure;
use Psr\Log\LoggerInterface;
use Throwable;
use Tueen\Telegram\Client\HttpClientInterface;
use Tueen\Telegram\Enums\ErrorHandlingMode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Running\RunningModeInterface;

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
    private ErrorHandlingMode $errorHandlingMode = ErrorHandlingMode::EXCEPTION;
    /** @var list<class-string<Throwable>> */
    private array $convertExceptionsToError = [ApiException::class];
    private ?RunningModeInterface $runningMode = null;

    public function __construct(string $botToken = '')
    {
        $this->botToken = $botToken;
    }

    #[\NoDiscard]
    public function withToken(string $botToken): static
    {
        $this->botToken = $botToken;
        return $this;
    }

    #[\NoDiscard]
    public function withApiServer(string $apiServer): static
    {
        $this->apiServer = $apiServer;
        return $this;
    }

    #[\NoDiscard]
    public function withTimeout(float $timeout): static
    {
        $this->timeout = $timeout;
        return $this;
    }

    #[\NoDiscard]
    public function withConnectTimeout(float $connectTimeout): static
    {
        $this->connectTimeout = $connectTimeout;
        return $this;
    }

    #[\NoDiscard]
    public function withProxy(?string $proxy): static
    {
        $this->proxy = $proxy;
        return $this;
    }

    #[\NoDiscard]
    public function withHttpClient(?HttpClientInterface $httpClient): static
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    /**
     * Configures the high-performance native PHP 8.5 CurlHttpClient.
     */
    #[\NoDiscard]
    public function withCurlClient(bool $persistent = true): static
    {
        $this->httpClient = new Client\CurlHttpClient(usePersistentShare: $persistent);
        return $this;
    }

    #[\NoDiscard]
    public function withLogger(?LoggerInterface $logger): static
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @param Closure(int $bytesUploaded, int $totalBytes, float $percentage): void $callback
     */
    #[\NoDiscard]
    public function withUploadProgress(?Closure $callback): static
    {
        $this->uploadProgress = $callback;
        return $this;
    }

    /**
     * @param Closure(int $bytesDownloaded, int $totalBytes, float $percentage): void $callback
     */
    #[\NoDiscard]
    public function withDownloadProgress(?Closure $callback): static
    {
        $this->downloadProgress = $callback;
        return $this;
    }

    #[\NoDiscard]
    public function withRetryCount(int $count): static
    {
        $this->retryCount = $count;
        return $this;
    }

    #[\NoDiscard]
    public function withTestEnvironment(bool $enabled = true): static
    {
        $this->testEnvironment = $enabled;
        return $this;
    }

    #[\NoDiscard]
    public function withErrorHandlingMode(ErrorHandlingMode $mode): static
    {
        $this->errorHandlingMode = $mode;
        return $this;
    }

    /**
     * Set error handling mode to Error object instead of throwing exceptions.
     *
     * @param list<class-string<Throwable>> $catchExceptions
     */
    #[\NoDiscard]
    public function withErrorObjectMode(array $catchExceptions = [ApiException::class]): static
    {
        $this->errorHandlingMode = ErrorHandlingMode::ERROR_OBJECT;
        $this->convertExceptionsToError = $catchExceptions;
        return $this;
    }

    /**
     * Set error handling mode to Exception (default).
     */
    #[\NoDiscard]
    public function withExceptionMode(): static
    {
        $this->errorHandlingMode = ErrorHandlingMode::EXCEPTION;
        return $this;
    }

    /**
     * Catch all throwables (including network, cURL, runtime) and convert them to Error objects.
     */
    #[\NoDiscard]
    public function withCatchAllErrors(): static
    {
        $this->errorHandlingMode = ErrorHandlingMode::ERROR_OBJECT;
        $this->convertExceptionsToError = [Throwable::class];
        return $this;
    }

    /**
     * Specify which exception classes should be converted to Error objects.
     *
     * @param list<class-string<Throwable>> $classes
     */
    #[\NoDiscard]
    public function withConvertExceptions(array $classes): static
    {
        $this->convertExceptionsToError = $classes;
        return $this;
    }

    #[\NoDiscard]
    public function withRunningMode(?RunningModeInterface $mode): static
    {
        $this->runningMode = $mode;
        return $this;
    }

    #[\NoDiscard]
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
            testEnvironment: $this->testEnvironment,
            errorHandlingMode: $this->errorHandlingMode,
            convertExceptionsToError: $this->convertExceptionsToError,
            runningMode: $this->runningMode
        );
    }
}
