<?php

declare(strict_types=1);

namespace Tueen\Telegram\Client;

use Closure;
use ReflectionClass;
use Tueen\Telegram\Config;
use Tueen\Telegram\Context\ContextResolver;
use Tueen\Telegram\Contracts\TelegramMethods;
use Tueen\Telegram\Enums\ErrorHandlingMode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\TelegramException;
use Tueen\Telegram\Formatting\Text;
use Tueen\Telegram\Keyboards\InlineKeyboard;
use Tueen\Telegram\Keyboards\ReplyKeyboard;
use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Pipeline\LoggingMiddleware;
use Tueen\Telegram\Pipeline\MiddlewareInterface;
use Tueen\Telegram\Pipeline\Pipeline;
use Tueen\Telegram\Pipeline\RetryMiddleware;
use Tueen\Telegram\Types\Custom\ArrayResult;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Types\Custom\IntegerResult;
use Tueen\Telegram\Types\Custom\StringResult;
use Tueen\Telegram\Types\Error;
use Tueen\Telegram\Types\File;
use Tueen\Telegram\Types\Type;

/**
 * Pure Telegram Bot API client responsible for sending API requests,
 * managing the HTTP middleware pipeline, file transfers, and method deserialization.
 *
 * @mixin TelegramMethods
 */
class TelegramClient
{
    private(set) Config $config;
    private(set) HttpClientInterface $httpClient;
    private(set) Pipeline $pipeline;
    public ?ContextResolver $contextResolver = null;

    /** @var list<callable> */
    private array $beforeRequestHooks = [];

    /** @var list<callable> */
    private array $afterRequestHooks = [];

    /** @var list<callable> */
    private array $errorHooks = [];

    /** @var list<callable> */
    private array $responseHooks = [];

    public function __construct(Config $config, ?ContextResolver $contextResolver = null)
    {
        $this->config = $config;
        $this->contextResolver = $contextResolver;
        $this->httpClient = $this->config->httpClient ?? new GuzzleHttpClient();
        $this->pipeline = new Pipeline();

        // Default middlewares
        $this->pipeline->pipe(new RetryMiddleware($this->config->retryCount));
        if ($this->config->logger !== null) {
            $this->pipeline->pipe(new LoggingMiddleware($this->config->logger));
        }
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
     * Sends an API Method through the execution pipeline.
     */
    public function send(
        Method $method,
        ?Closure $uploadProgress = null,
        ?Closure $downloadProgress = null,
        ?RequestOptions $options = null
    ): mixed {
        if ($this->contextResolver !== null) {
            $this->contextResolver->resolveMethod($method);
        }

        [$params, $files] = $method->buildRequestData();

        $endpoint = $method->endpoint;
        $requestTimeout = null;

        // Long-polling: ensure HTTP client timeout exceeds Telegram server wait timeout
        if ($endpoint === 'getUpdates' && isset($params['timeout']) && is_numeric($params['timeout'])) {
            $pollTimeout = (float)$params['timeout'];
            $requestTimeout = max($pollTimeout + 15.0, $this->config->timeout);
        } elseif (!empty($files)) {
            $requestTimeout = max(120.0, $this->config->timeout);
        }

        if ($options?->timeout !== null) {
            $requestTimeout = $options->timeout;
        }

        $request = new Request(
            endpoint: $endpoint,
            parameters: $params,
            files: $files,
            httpMethod: $method->httpMethod,
            uploadProgress: $uploadProgress ?? $options?->uploadProgress ?? $this->config->uploadProgress,
            downloadProgress: $downloadProgress ?? $options?->downloadProgress ?? $this->config->downloadProgress,
            timeout: $requestTimeout,
            connectTimeout: $options?->connectTimeout
        );

        $this->triggerBeforeRequest($request);

        try {
            $response = $this->pipeline->run(
                $request,
                $this->config,
                fn(Request $req, Config $cfg): Response => $this->httpClient->send($cfg, $req)
            );

            $this->triggerAfterRequest($response, $request);

            if (!$response->ok) {
                throw ApiException::fromResponse($response->data);
            }

            $result = $response->result;
            $returnInfo = $method->returnTypeInfo;
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
     * Downloads a file from Telegram servers.
     */
    public function downloadFile(mixed $file, mixed $destination, ?callable $progress = null): BooleanResult|Error
    {
        $filePath = null;

        if ($file instanceof File) {
            $filePath = $file->filePath;
        } elseif (is_string($file)) {
            if (str_contains($file, '/') || str_contains($file, '.')) {
                $filePath = $file;
            } else {
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

    /**
     * Dynamic method invocation for any Telegram Bot API method.
     */
    public function __call(string $name, array $arguments): mixed
    {
        $options = null;
        if (array_key_exists('_', $arguments)) {
            $options = RequestOptions::from($arguments['_']);
            unset($arguments['_']);
        } elseif (count($arguments) === 1 && isset($arguments[0]) && is_array($arguments[0]) && array_key_exists('_', $arguments[0])) {
            $options = RequestOptions::from($arguments[0]['_']);
            unset($arguments[0]['_']);
        }

        $className = 'Tueen\\Telegram\\Methods\\' . ucfirst($name);

        if (class_exists($className)) {
            $methodInstance = $this->instantiateMethod($className, $arguments);
            return $this->send($methodInstance, options: $options);
        }

        $dynamicMethod = new class($name, $arguments) extends Method {
            public string $endpoint {
                get => $this->endpointName;
            }

            public function __construct(
                private readonly string $endpointName,
                array $args
            ) {
                if (count($args) === 1 && isset($args[0]) && is_array($args[0])) {
                    $this->customParameters = $args[0];
                } else {
                    foreach ($args as $k => $v) {
                        $this->customParameters[Type::toSnakeCase((string)$k)] = $v;
                    }
                }
            }
        };

        return $this->send($dynamicMethod, options: $options);
    }

    /**
     * Dynamically instantiates a Method class matching named or positional arguments.
     */
    public function instantiateMethod(string $className, array $arguments): Method
    {
        if ($this->contextResolver !== null) {
            $arguments = $this->contextResolver->resolveArguments($className, $arguments);
        }

        $reflection = new ReflectionClass($className);
        $constructor = $reflection->getConstructor();

        if ($constructor === null || empty($arguments)) {
            return $reflection->newInstance();
        }

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

            $val = null;
            $found = false;

            if (array_key_exists($pName, $arguments)) {
                $val = $arguments[$pName];
                $consumedKeys[$pName] = true;
                $found = true;
            } elseif (array_key_exists($snake, $arguments)) {
                $val = $arguments[$snake];
                $consumedKeys[$snake] = true;
                $found = true;
            } elseif ($param->isDefaultValueAvailable()) {
                if (!array_key_exists($pName, $passedArgs)) {
                    $passedArgs[$pName] = $param->getDefaultValue();
                }
            }

            if ($found) {
                if ($val instanceof Text) {
                    $passedArgs[$pName] = (string) $val;
                    if (!isset($arguments['parse_mode']) && !isset($arguments['parseMode']) && !isset($passedArgs['parseMode'])) {
                        $passedArgs['parseMode'] = $val->parseMode;
                    }
                } elseif ($val instanceof InlineKeyboard || $val instanceof ReplyKeyboard) {
                    $passedArgs[$pName] = $val->build();
                } else {
                    $passedArgs[$pName] = $val;
                }
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
    public function unwrapResult(mixed $result, ?string $expectedType = null, bool $isArray = false): mixed
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

    public function shouldCatchException(\Throwable $e): bool
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
}
