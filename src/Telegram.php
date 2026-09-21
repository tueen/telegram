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
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\TelegramException;
use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Pipeline\LoggingMiddleware;
use Tueen\Telegram\Pipeline\MiddlewareInterface;
use Tueen\Telegram\Pipeline\Pipeline;
use Tueen\Telegram\Pipeline\RetryMiddleware;
use Tueen\Telegram\Types\Custom\ArrayResult;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Types\Custom\IntegerResult;
use Tueen\Telegram\Types\Custom\StringResult;
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

        $response = $this->pipeline->run(
            $request,
            $this->config,
            fn(Request $req, Config $cfg): Response => $this->httpClient->send($cfg, $req)
        );

        if (!$response->isOk()) {
            throw ApiException::fromResponse($response->data);
        }

        $result = $response->getResult();
        $returnInfo = $method->getReturnTypeInfo();

        return $this->unwrapResult($result, $returnInfo?->type, $returnInfo?->isArray ?? false);
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

        foreach ($parameters as $param) {
            $pName = $param->getName();
            $snake = Type::toSnakeCase($pName);

            if (array_key_exists($pName, $arguments)) {
                $passedArgs[$pName] = $arguments[$pName];
            } elseif (array_key_exists($snake, $arguments)) {
                $passedArgs[$pName] = $arguments[$snake];
            } elseif ($param->isDefaultValueAvailable()) {
                $passedArgs[$pName] = $param->getDefaultValue();
            }
        }

        return $reflection->newInstanceArgs($passedArgs);
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
    public function downloadFile(mixed $file, mixed $destination, ?callable $progress = null): BooleanResult
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
                /** @var File $fileObj */
                $fileObj = $this->getFile(fileId: $file);
                $filePath = $fileObj->filePath;
            }
        }

        if (empty($filePath)) {
            throw new TelegramException("Unable to resolve file path for download.");
        }

        $ok = $this->httpClient->download($this->config, $filePath, $destination, $progress);
        return new BooleanResult($ok);
    }

    /**
     * Helper to parse and handle incoming webhook requests.
     */
    public function handleWebhook(?string $rawInput = null): Update
    {
        if ($rawInput === null) {
            $rawInput = file_get_contents('php://input');
        }

        if (empty($rawInput)) {
            throw new TelegramException("Empty webhook payload received.");
        }

        $data = json_decode($rawInput, true);
        if (!is_array($data)) {
            throw new TelegramException("Invalid JSON payload in webhook: " . json_last_error_msg());
        }

        return new Update($data);
    }

    /**
     * Long polling update generator.
     *
     * @return Generator<Update>
     */
    public function poll(int $timeout = 30, int $limit = 100, ?array $allowedUpdates = null): Generator
    {
        $offset = 0;

        while (true) {
            try {
                /** @var Update[] $updates */
                $updates = $this->getUpdates(
                    offset: $offset,
                    limit: $limit,
                    timeout: $timeout,
                    allowedUpdates: $allowedUpdates
                );

                foreach ($updates as $update) {
                    $offset = max($offset, $update->updateId + 1);
                    yield $update;
                }
            } catch (TelegramException $e) {
                // Yield error or backoff
                sleep(2);
            }
        }
    }

    public function getConfig(): Config
    {
        return $this->config;
    }
}
