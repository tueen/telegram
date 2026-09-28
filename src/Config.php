<?php

declare(strict_types=1);

namespace Tueen\Telegram;

use Closure;
use Psr\Log\LoggerInterface;
use Tueen\Telegram\Client\HttpClientInterface;
use Tueen\Telegram\Enums\ErrorHandlingMode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Running\RunningModeInterface;

use Uri\InvalidUriException;
use Uri\Rfc3986\Uri;

class Config
{
    public function __construct(
        final public readonly string $botToken,
        final public readonly string $apiServer = 'https://api.telegram.org',
        final public readonly float $timeout = 30.0,
        final public readonly float $connectTimeout = 10.0,
        final public readonly ?string $proxy = null,
        final public readonly ?HttpClientInterface $httpClient = null,
        final public readonly ?LoggerInterface $logger = null,
        final public readonly ?Closure $uploadProgress = null,
        final public readonly ?Closure $downloadProgress = null,
        final public readonly int $retryCount = 3,
        final public readonly bool $testEnvironment = false,
        final public readonly ErrorHandlingMode $errorHandlingMode = ErrorHandlingMode::EXCEPTION,
        /** @var list<class-string<\Throwable>> */
        final public readonly array $convertExceptionsToError = [ApiException::class],
        final public readonly ?RunningModeInterface $runningMode = null,
        final public readonly mixed $container = null
    ) {}

    #[\NoDiscard]
    public static function builder(string $botToken = ''): ConfigBuilder
    {
        return new ConfigBuilder($botToken);
    }

    #[\NoDiscard]
    public function withToken(string $botToken): self
    {
        return clone($this, ['botToken' => $botToken]);
    }

    #[\NoDiscard]
    public function withApiServer(string $apiServer): self
    {
        return clone($this, ['apiServer' => $apiServer]);
    }

    #[\NoDiscard]
    public function withTimeout(float $timeout): self
    {
        return clone($this, ['timeout' => $timeout]);
    }

    #[\NoDiscard]
    public function withConnectTimeout(float $connectTimeout): self
    {
        return clone($this, ['connectTimeout' => $connectTimeout]);
    }

    #[\NoDiscard]
    public function withProxy(?string $proxy): self
    {
        return clone($this, ['proxy' => $proxy]);
    }

    #[\NoDiscard]
    public function withHttpClient(?HttpClientInterface $httpClient): self
    {
        return clone($this, ['httpClient' => $httpClient]);
    }

    #[\NoDiscard]
    public function withLogger(?LoggerInterface $logger): self
    {
        return clone($this, ['logger' => $logger]);
    }

    #[\NoDiscard]
    public function withUploadProgress(?Closure $callback): self
    {
        return clone($this, ['uploadProgress' => $callback]);
    }

    #[\NoDiscard]
    public function withDownloadProgress(?Closure $callback): self
    {
        return clone($this, ['downloadProgress' => $callback]);
    }

    #[\NoDiscard]
    public function withRetryCount(int $retryCount): self
    {
        return clone($this, ['retryCount' => $retryCount]);
    }

    #[\NoDiscard]
    public function withTestEnvironment(bool $testEnvironment): self
    {
        return clone($this, ['testEnvironment' => $testEnvironment]);
    }

    #[\NoDiscard]
    public function withErrorHandlingMode(ErrorHandlingMode $errorHandlingMode): self
    {
        return clone($this, ['errorHandlingMode' => $errorHandlingMode]);
    }

    /**
     * @param list<class-string<\Throwable>> $classes
     */
    #[\NoDiscard]
    public function withConvertExceptionsToError(array $classes): self
    {
        return clone($this, ['convertExceptionsToError' => $classes]);
    }

    #[\NoDiscard]
    public function withRunningMode(?RunningModeInterface $runningMode): self
    {
        return clone($this, ['runningMode' => $runningMode]);
    }

    #[\NoDiscard]
    public function withContainer(mixed $container): self
    {
        return clone($this, ['container' => $container]);
    }

    public function getBaseApiUrl(): string
    {
        $server = rtrim($this->apiServer, '/');
        $test = $this->testEnvironment ? '/test' : '';
        return "{$server}/bot{$this->botToken}{$test}";
    }

    public function getBaseFileUrl(): string
    {
        $server = rtrim($this->apiServer, '/');
        $test = $this->testEnvironment ? '/test' : '';
        return "{$server}/file/bot{$this->botToken}{$test}";
    }

    /**
     * Resolves the API base URI as a standards-compliant Uri instance.
     * @throws InvalidUriException
     */
    #[\NoDiscard]
    public function getApiUri(): Uri
    {
        return new Uri($this->getBaseApiUrl());
    }

    /**
     * Resolves the File base URI as a standards-compliant Uri instance.
     * @throws InvalidUriException
     */
    #[\NoDiscard]
    public function getFileUri(): Uri
    {
        return new Uri($this->getBaseFileUrl());
    }
}
