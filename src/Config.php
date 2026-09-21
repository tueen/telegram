<?php

declare(strict_types=1);

namespace Tueen\Telegram;

use Closure;
use Psr\Log\LoggerInterface;
use Tueen\Telegram\Client\HttpClientInterface;
use Tueen\Telegram\Enums\ErrorHandlingMode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Running\RunningModeInterface;

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
        public readonly ?RunningModeInterface $runningMode = null
    ) {}

    public static function builder(string $botToken = ''): ConfigBuilder
    {
        return new ConfigBuilder($botToken);
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
}
