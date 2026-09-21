<?php

declare(strict_types=1);

namespace Tueen\Telegram;

use Closure;
use Generator;
use ReflectionClass;
use Tueen\Telegram\Client\GuzzleHttpClient;
use Tueen\Telegram\Client\HttpClientInterface;
use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Client\Response;
use Tueen\Telegram\Enums\ErrorHandlingMode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\TelegramException;
use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Pipeline\LoggingMiddleware;
use Tueen\Telegram\Pipeline\MiddlewareInterface;
use Tueen\Telegram\Pipeline\Pipeline;
use Tueen\Telegram\Pipeline\RetryMiddleware;
use Tueen\Telegram\Running\PollingMode;
use Tueen\Telegram\Running\RunningModeInterface;
use Tueen\Telegram\Running\WebhookMode;
use Tueen\Telegram\Types\Custom\ArrayResult;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Types\Custom\IntegerResult;
use Tueen\Telegram\Types\Custom\StringResult;
use Tueen\Telegram\Types\Error;
use Tueen\Telegram\Types\File;
use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Types\Update;

/**
 * Tueen Telegram Client - The Royal Client for Telegram Bot API.
 *
 * @mixin \Tueen\Telegram\Contracts\TelegramMethods
 */
class Telegram
{
    private Config $config;
    private HttpClientInterface $httpClient;
    private Pipeline $pipeline;
    private ?RunningModeInterface $runningMode = null;

    /** @var list<callable> */
    private array $beforeRequestHooks = [];

    /** @var list<callable> */
    private array $afterRequestHooks = [];

    /** @var list<callable> */
    private array $errorHooks = [];

    /** @var list<callable> */
    private array $responseHooks = [];

    public function __construct(string|Config $tokenOrConfig)
    {
        if (is_string($tokenOrConfig)) {
            $this->config = new Config(botToken: $tokenOrConfig);
        } else {
            $this->config = $tokenOrConfig;
        }

        $this->httpClient = $this->config->httpClient ?? new GuzzleHttpClient();
        $this->pipeline = new Pipeline();

        // Default middlewares
        $this->pipeline->pipe(new RetryMiddleware($this->config->retryCount));
        if ($this->config->logger !== null) {
            $this->pipeline->pipe(new LoggingMiddleware($this->config->logger));
        }

        $this->runningMode = $this->config->runningMode;
    }

    /**
     * Fluent factory builder.
     */
    public static function create(string $botToken): ConfigBuilder
    {
        return new ConfigBuilder($botToken);
    }

    /**
     * Appends a middleware to the execution pipeline.
     */
    public function pipe(MiddlewareInterface|Closure $middleware): static
    {
        $this->pipeline->pipe($middleware);
        return $this;
    }

    /**
     * Register a callback to be called before sending a request.
     *
     * @param callable(Request, Config): void $callback
     */
    public function onBeforeRequest(callable $callback): static
    {
        $this->beforeRequestHooks[] = $callback;
        return $this;
    }

    /**
     * Register a callback to be called after a raw HTTP response is received.
     *
     * @param callable(Response, Request): void $callback
     */
    public function onAfterRequest(callable $callback): static
    {
        $this->afterRequestHooks[] = $callback;
        return $this;
    }

    /**
     * Register a callback to be called when an error occurs.
     *
     * @param callable(\Throwable, Request): void $callback
     */
    public function onError(callable $callback): static
    {
        $this->errorHooks[] = $callback;
        return $this;
    }

    /**
     * Register a callback to be called when a final response (Type or Error) is produced.
     *
     * @param callable(Type, Request): void $callback
     */
    public function onResponse(callable $callback): static
    {
        $this->responseHooks[] = $callback;
        return $this;
    }

    /**
     * Sends a Method object to the Telegram Bot API.
     */
    public function send(Method $method, ?Closure $uploadProgress = null, ?Closure $downloadProgress = null): mixed
    {
        [$params, $files] = $method->buildRequestData();

        $request = new Request(
            endpoint: $method->getEndpoint(),
            parameters: $params,
            files: $files,
            httpMethod: $method->getHttpMethod(),
            uploadProgress: $uploadProgress ?? $this->config->uploadProgress,
            downloadProgress: $downloadProgress ?? $this->config->downloadProgress
        );

        $this->triggerBeforeRequest($request);

        try {
            $response = $this->pipeline->run(
                $request,
                $this->config,
                fn(Request $req, Config $cfg): Response => $this->httpClient->send($cfg, $req)
            );

            $this->triggerAfterRequest($response, $request);

            if (!$response->isOk()) {
                throw ApiException::fromResponse($response->data);
            }

            $result = $response->getResult();
            $returnInfo = $method->getReturnTypeInfo();
            $unwrapped = $this->unwrapResult($result, $returnInfo?->type, $returnInfo?->isArray ?? false);

            if ($unwrapped instanceof Type) {
                $this->triggerResponse($unwrapped, $request);
            }

            return $unwrapped;
        } catch (\Throwable $e) {
            $this->triggerError($e, $request);

            if ($this->shouldCatchException($e)) {
                if ($e instanceof ApiException && isset($response) && is_array($response->data)) {
                    $error = Error::fromResponse($response->data, $e);
                } else {
                    $error = Error::fromThrowable($e);
                }

                $this->triggerResponse($error, $request);
                return $error;
            }

            throw $e;
        }
    }

    /**
     * Dynamic method invocation for any Telegram Bot API method.
     *
     * Example:
     * $telegram->sendMessage(chatId: 123456, text: 'Hello, Queen!');
     */
    public function __call(string $name, array $arguments): mixed
    {
        $className = 'Tueen\\Telegram\\Methods\\' . ucfirst($name);

        if (class_exists($className)) {
            $methodInstance = $this->instantiateMethod($className, $arguments);
            return $this->send($methodInstance);
        }

        // Dynamic fallback: build a dynamic Method instance
        $dynamicMethod = new class($name, $arguments) extends Method {
            public function __construct(
                private readonly string $endpointName,
                array $args
            ) {
                // If single associative array passed, or named args
                if (count($args) === 1 && isset($args[0]) && is_array($args[0])) {
                    $this->parameters = $args[0];
                } else {
                    foreach ($args as $k => $v) {
                        $this->parameters[Type::toSnakeCase((string)$k)] = $v;
                    }
                }
            }

            public function getEndpoint(): string
            {
                return $this->endpointName;
            }
        };

        return $this->send($dynamicMethod);
    }

    /**
     * Dynamically instantiates a Method class matching named or positional arguments.
     */
    private function instantiateMethod(string $className, array $arguments): Method
    {
        $reflection = new ReflectionClass($className);
        $constructor = $reflection->getConstructor();

        if ($constructor === null || empty($arguments)) {
            return $reflection->newInstance();
        }

        // If a single associative array is provided, e.g. $telegram->sendMessage([...])
        if (count($arguments) === 1 && isset($arguments[0]) && is_array($arguments[0])) {
            $arguments = $arguments[0];
        }

        $parameters = $constructor->getParameters();
        $passedArgs = [];
        $consumedKeys = [];

        foreach ($parameters as $param) {
            if ($param->isVariadic()) {
                continue;
            }

            $pName = $param->getName();
            $snake = Type::toSnakeCase($pName);

            if (array_key_exists($pName, $arguments)) {
                $passedArgs[$pName] = $arguments[$pName];
                $consumedKeys[$pName] = true;
            } elseif (array_key_exists($snake, $arguments)) {
                $passedArgs[$pName] = $arguments[$snake];
                $consumedKeys[$snake] = true;
            } elseif ($param->isDefaultValueAvailable()) {
                $passedArgs[$pName] = $param->getDefaultValue();
            }
        }

        $extraArgs = [];
        foreach ($arguments as $k => $v) {
            if (!isset($consumedKeys[$k])) {
                $extraArgs[$k] = $v;
            }
        }

        /** @var Method $instance */
        $instance = $reflection->newInstanceArgs($passedArgs);
        if (!empty($extraArgs)) {
            $instance->handleExtraParameters($extraArgs);
        }

        return $instance;
    }

    /**
     * Unwraps and deserializes API response result.
     */
    private function unwrapResult(mixed $result, ?string $expectedType = null, bool $isArray = false): mixed
    {
        if (is_bool($result)) {
            return new BooleanResult($result);
        }

        if (is_int($result)) {
            return new IntegerResult($result);
        }

        if (is_string($result)) {
            return new StringResult($result);
        }

        if ($result === null) {
            return null;
        }

        if ($isArray && is_array($result)) {
            $targetClass = $expectedType ?? Type::class;
            $items = Type::castArrayOf($result, $targetClass);
            return new ArrayResult($items);
        }

        if (is_array($result)) {
            if (array_is_list($result)) {
                $targetClass = $expectedType ?? Type::class;
                $items = Type::castArrayOf($result, $targetClass);
                return new ArrayResult($items);
            }

            $targetClass = $expectedType ?? Type::class;
            return Type::factory($targetClass, $result);
        }

        return $result;
    }

    /**
     * Downloads a file from Telegram.
     *
     * @param string|File $file File ID, file path, or File type instance
     * @param resource|string $destination Target local path or stream resource
     * @param callable|null $progress fn(int $downloadedBytes, int $totalBytes, float $percentage)
     */
    public function downloadFile(mixed $file, mixed $destination, ?callable $progress = null): BooleanResult|Error
    {
        $filePath = null;

        if ($file instanceof File) {
            $filePath = $file->filePath;
        } elseif (is_string($file)) {
            // Check if it's already a relative path with extension
            if (str_contains($file, '/') || str_contains($file, '.')) {
                $filePath = $file;
            } else {
                // It's a file_id, retrieve File object first
                $fileObj = $this->getFile(fileId: $file);
                if ($fileObj instanceof Error) {
                    return $fileObj;
                }
                $filePath = $fileObj->filePath;
            }
        }

        if (empty($filePath)) {
            $exception = new TelegramException("Unable to resolve file path for download.");
            if ($this->shouldCatchException($exception)) {
                return Error::fromThrowable($exception);
            }
            throw $exception;
        }

        try {
            $ok = $this->httpClient->download($this->config, $filePath, $destination, $progress);
            return new BooleanResult($ok);
        } catch (\Throwable $e) {
            if ($this->shouldCatchException($e)) {
                return Error::fromThrowable($e);
            }
            throw $e;
        }
    }

    private function triggerBeforeRequest(Request $request): void
    {
        foreach ($this->beforeRequestHooks as $hook) {
            $hook($request, $this->config);
        }
    }

    private function triggerAfterRequest(Response $response, Request $request): void
    {
        foreach ($this->afterRequestHooks as $hook) {
            $hook($response, $request);
        }
    }

    private function triggerError(\Throwable $error, Request $request): void
    {
        foreach ($this->errorHooks as $hook) {
            $hook($error, $request);
        }
    }

    private function triggerResponse(Type $result, Request $request): void
    {
        foreach ($this->responseHooks as $hook) {
            $hook($result, $request);
        }
    }

    private function shouldCatchException(\Throwable $e): bool
    {
        if ($this->config->errorHandlingMode !== ErrorHandlingMode::ERROR_OBJECT) {
            return false;
        }

        foreach ($this->config->convertExceptionsToError as $class) {
            if ($e instanceof $class) {
                return true;
            }
        }

        return false;
    }

    /**
     * Sets the active running mode (e.g. WebhookMode or PollingMode).
     */
    public function setRunningMode(RunningModeInterface $mode): static
    {
        $this->runningMode = $mode;
        return $this;
    }

    /**
     * Gets the active running mode, defaulting to WebhookMode.
     */
    public function getRunningMode(): RunningModeInterface
    {
        return $this->runningMode ??= new WebhookMode();
    }

    /**
     * Resolves an incoming update using the active running mode or provided raw payload.
     */
    public function getUpdate(?string $rawInput = null): Update
    {
        $mode = $this->getRunningMode();
        if ($rawInput !== null && $mode instanceof WebhookMode) {
            $mode->setRawInput($rawInput);
        }

        if ($mode instanceof WebhookMode) {
            return $mode->getUpdate($this);
        }

        $update = $mode->processUpdate($this);
        if ($update instanceof Update) {
            return $update;
        }

        throw new TelegramException("Running mode did not return an Update object.");
    }

    /**
     * Executes the bot with an optional update handler according to the configured running mode.
     *
     * @param callable(Update): mixed|null $handler
     */
    public function run(?callable $handler = null): mixed
    {
        return $this->getRunningMode()->processUpdate($this, $handler);
    }

    /**
     * Alias for getUpdate() for backward compatibility.
     */
    public function handleWebhook(?string $rawInput = null): Update
    {
        return $this->getUpdate($rawInput);
    }

    /**
     * Long polling update generator.
     *
     * @return Generator<Update>
     */
    public function poll(int $timeout = 30, int $limit = 100, ?array $allowedUpdates = null): Generator
    {
        $mode = new PollingMode(timeout: $timeout, limit: $limit, allowedUpdates: $allowedUpdates);
        return $mode->getUpdatesGenerator($this);
    }

    public function getConfig(): Config
    {
        return $this->config;
    }
}
