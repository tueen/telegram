<?php

declare(strict_types=1);

namespace Tueen\Telegram;

use Closure;
use Psr\Log\LoggerInterface;
use Tueen\Telegram\Client\HttpClientInterface;
use Tueen\Telegram\Enums\ErrorHandlingMode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Running\AutoMode;
use Tueen\Telegram\Running\PollingMode;
use Tueen\Telegram\Running\RunningModeInterface;
use Tueen\Telegram\Running\WebhookMode;

use Uri\InvalidUriException;
use Uri\Rfc3986\Uri;

class Config
{
    public function __construct(
        public readonly string $botToken,
        public readonly string $apiServer = 'https://api.telegram.org',
        public readonly float $timeout = 30.0,
        public readonly float $connectTimeout = 10.0,
        public readonly ?string $proxy = null,
        public readonly ?HttpClientInterface $httpClient = null,
        public readonly ?LoggerInterface $logger = null,
        public readonly ?Closure $uploadProgress = null,
        public readonly ?Closure $downloadProgress = null,
        public readonly int $retryCount = 3,
        public readonly bool $testEnvironment = false,
        public readonly ErrorHandlingMode $errorHandlingMode = ErrorHandlingMode::EXCEPTION,
        /** @var list<class-string<\Throwable>> */
        public readonly array $convertExceptionsToError = [ApiException::class],
        public readonly ?RunningModeInterface $runningMode = null,
        public readonly mixed $container = null
    ) {}

    #[\NoDiscard]
    public static function builder(string $botToken = ''): ConfigBuilder
    {
        return new ConfigBuilder($botToken);
    }

    private function copyWith(array $overrides): self
    {
        return new self(
            botToken: $overrides['botToken'] ?? $this->botToken,
            apiServer: $overrides['apiServer'] ?? $this->apiServer,
            timeout: $overrides['timeout'] ?? $this->timeout,
            connectTimeout: $overrides['connectTimeout'] ?? $this->connectTimeout,
            proxy: array_key_exists('proxy', $overrides) ? $overrides['proxy'] : $this->proxy,
            httpClient: array_key_exists('httpClient', $overrides) ? $overrides['httpClient'] : $this->httpClient,
            logger: array_key_exists('logger', $overrides) ? $overrides['logger'] : $this->logger,
            uploadProgress: array_key_exists('uploadProgress', $overrides) ? $overrides['uploadProgress'] : $this->uploadProgress,
            downloadProgress: array_key_exists('downloadProgress', $overrides) ? $overrides['downloadProgress'] : $this->downloadProgress,
            retryCount: $overrides['retryCount'] ?? $this->retryCount,
            testEnvironment: $overrides['testEnvironment'] ?? $this->testEnvironment,
            errorHandlingMode: $overrides['errorHandlingMode'] ?? $this->errorHandlingMode,
            convertExceptionsToError: $overrides['convertExceptionsToError'] ?? $this->convertExceptionsToError,
            runningMode: array_key_exists('runningMode', $overrides) ? $overrides['runningMode'] : $this->runningMode,
            container: array_key_exists('container', $overrides) ? $overrides['container'] : $this->container,
        );
    }

    #[\NoDiscard]
    public function withToken(string $botToken): self
    {
        return $this->copyWith(['botToken' => $botToken]);
    }

    #[\NoDiscard]
    public function withApiServer(string $apiServer): self
    {
        return $this->copyWith(['apiServer' => $apiServer]);
    }

    #[\NoDiscard]
    public function withTimeout(float $timeout): self
    {
        return $this->copyWith(['timeout' => $timeout]);
    }

    #[\NoDiscard]
    public function withConnectTimeout(float $connectTimeout): self
    {
        return $this->copyWith(['connectTimeout' => $connectTimeout]);
    }

    #[\NoDiscard]
    public function withProxy(?string $proxy): self
    {
        return $this->copyWith(['proxy' => $proxy]);
    }

    #[\NoDiscard]
    public function withHttpClient(?HttpClientInterface $httpClient): self
    {
        return $this->copyWith(['httpClient' => $httpClient]);
    }

    #[\NoDiscard]
    public function withLogger(?LoggerInterface $logger): self
    {
        return $this->copyWith(['logger' => $logger]);
    }

    #[\NoDiscard]
    public function withUploadProgress(?Closure $callback): self
    {
        return $this->copyWith(['uploadProgress' => $callback]);
    }

    #[\NoDiscard]
    public function withDownloadProgress(?Closure $callback): self
    {
        return $this->copyWith(['downloadProgress' => $callback]);
    }

    #[\NoDiscard]
    public function withRetryCount(int $retryCount): self
    {
        return $this->copyWith(['retryCount' => $retryCount]);
    }

    #[\NoDiscard]
    public function withTestEnvironment(bool $testEnvironment): self
    {
        return $this->copyWith(['testEnvironment' => $testEnvironment]);
    }

    #[\NoDiscard]
    public function withErrorHandlingMode(ErrorHandlingMode $errorHandlingMode): self
    {
        return $this->copyWith(['errorHandlingMode' => $errorHandlingMode]);
    }

    /**
     * @param list<class-string<\Throwable>> $classes
     */
    #[\NoDiscard]
    public function withConvertExceptionsToError(array $classes): self
    {
        return $this->copyWith(['convertExceptionsToError' => $classes]);
    }

    #[\NoDiscard]
    public function withRunningMode(?RunningModeInterface $runningMode): self
    {
        return $this->copyWith(['runningMode' => $runningMode]);
    }

    #[\NoDiscard]
    public function withAutoMode(
        ?PollingMode $polling = null,
        ?WebhookMode $webhook = null,
        bool $autoDeleteWebhook = false,
        bool $dropPendingUpdatesOnDelete = false,
        ?callable $detector = null
    ): self {
        return $this->withRunningMode(new AutoMode(
            pollingMode: $polling,
            webhookMode: $webhook,
            autoDeleteWebhook: $autoDeleteWebhook,
            dropPendingUpdatesOnDelete: $dropPendingUpdatesOnDelete,
            detector: $detector
        ));
    }

    #[\NoDiscard]
    public function withContainer(mixed $container): self
    {
        return $this->copyWith(['container' => $container]);
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
    public function getApiUri(): mixed
    {
        if (class_exists(Uri::class)) {
            return new Uri($this->getBaseApiUrl());
        }
        return $this->getBaseApiUrl();
    }

    /**
     * Resolves the File base URI as a standards-compliant Uri instance.
     * @throws InvalidUriException
     */
    #[\NoDiscard]
    public function getFileUri(): mixed
    {
        if (class_exists(Uri::class)) {
            return new Uri($this->getBaseFileUrl());
        }
        return $this->getBaseFileUrl();
    }
}
