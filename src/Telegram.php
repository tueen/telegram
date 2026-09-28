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
use Tueen\Telegram\Enums\UpdateType;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\TelegramException;
use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Pipeline\LoggingMiddleware;
use Tueen\Telegram\Pipeline\MiddlewareInterface;
use Tueen\Telegram\Pipeline\Pipeline;
use Tueen\Telegram\Pipeline\RetryMiddleware;
use Tueen\Telegram\Routing\Router;
use Tueen\Telegram\Running\PollingMode;
use Tueen\Telegram\Running\RunningModeInterface;
use Tueen\Telegram\Running\WebhookMode;
use Tueen\Telegram\Testing\TelegramFake;
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
    /**
     * Supported Telegram Bot API version.
     */
    public const string BOT_API_VERSION = '10.3';

    /**
     * Alias for BOT_API_VERSION.
     */
    public const string API_VERSION = self::BOT_API_VERSION;

    /**
     * Current resolved Update instance.
     * Defaults to null until resolved by Webhook or Polling runner.
     */
    private(set) ?Update $update = null;

    private Config $config;
    private HttpClientInterface $httpClient;
    private Pipeline $pipeline;
    private ?RunningModeInterface $runningMode = null;
    private mixed $container = null;

    /** @var list<mixed> */
    private array $updateHandlers = [];

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
        $this->container = $this->config->container;
    }

    /**
     * Fluent factory builder.
     */
    #[\NoDiscard]
    public static function create(string $botToken): ConfigBuilder
    {
        return new ConfigBuilder($botToken);
    }

    /**
     * Creates an in-memory testing fake instance of the Telegram client.
     */
    #[\NoDiscard]
    public static function fake(array $responses = [], string $botToken = 'FAKE_BOT_TOKEN'): TelegramFake
    {
        return new TelegramFake($botToken, $responses);
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
                if ($val instanceof \Tueen\Telegram\Formatting\Text) {
                    $passedArgs[$pName] = (string) $val;
                    if (!isset($arguments['parse_mode']) && !isset($arguments['parseMode']) && !isset($passedArgs['parseMode'])) {
                        $passedArgs['parseMode'] = $val->parseMode();
                    }
                } elseif ($val instanceof \Tueen\Telegram\Keyboards\InlineKeyboard || $val instanceof \Tueen\Telegram\Keyboards\ReplyKeyboard) {
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
     * Manually updates the active Update instance.
     */
    public function setUpdate(?Update $update): static
    {
        $this->update = $update;
        return $this;
    }

    /**
     * Sets a dependency injection container or callable resolver for handler instantiation.
     */
    public function setContainer(mixed $container): static
    {
        $this->container = $container;
        return $this;
    }

    /**
     * Gets the configured container or resolver.
     */
    public function getContainer(): mixed
    {
        return $this->container ?? $this->config->container;
    }

    protected ?\Tueen\Telegram\Flow\FlowManager $flowManager = null;

    /**
     * Retrieves or lazily creates the FlowManager instance.
     */
    public function flowManager(): \Tueen\Telegram\Flow\FlowManager
    {
        return $this->flowManager ??= new \Tueen\Telegram\Flow\FlowManager();
    }

    /**
     * Sets a custom FlowManager instance.
     */
    public function setFlowManager(\Tueen\Telegram\Flow\FlowManager $manager): static
    {
        $this->flowManager = $manager;
        return $this;
    }

    /**
     * Sets the active state storage driver for flows (e.g. MemoryStateStore, FileStateStore).
     */
    public function setFlowStore(\Tueen\Telegram\Flow\Storage\StateStoreInterface $store): static
    {
        $this->flowManager()->setStore($store);
        return $this;
    }

    /**
     * Starts a multi-step Flow for the user associated with the update.
     *
     * @param class-string<\Tueen\Telegram\Flow\Flow> $flowClass
     */
    public function startFlow(
        string $flowClass,
        ?Update $update = null,
        string $initialStep = 'start',
        array $initialData = []
    ): \Tueen\Telegram\Flow\Flow {
        $resolvedUpdate = $update ?? $this->update;
        if ($resolvedUpdate === null) {
            throw new TelegramException("Cannot start Flow: no active Update found. Pass Update explicitly or run inside an update handler.");
        }

        return $this->flowManager()->startFlow(
            flowClass: $flowClass,
            update: $resolvedUpdate,
            bot: $this,
            initialStep: $initialStep,
            initialData: $initialData
        );
    }

    /**
     * Registers one or more update handlers to be invoked when the bot is run.
     *
     * Handlers can be:
     * - A callable: fn(Update $update, Telegram $bot) => ...
     * - An invokable class string: MyHandler::class
     * - An invokable class instance: new MyHandler()
     * - An array/list of any of the above
     */
    public function handle(mixed ...$handlers): static
    {
        foreach ($this->normalizeHandlers($handlers) as $handler) {
            $this->updateHandlers[] = $handler;
        }
        return $this;
    }

    /**
     * Parses a raw JSON string or array update payload into a strongly-typed Update object.
     * Callable-friendly for use in PHP 8.5 pipe operator (|>) pipelines:
     * $update = $jsonString |> $telegram->parseUpdate(...);
     */
    #[\NoDiscard]
    public function parseUpdate(string|array $payload): Update
    {
        if (is_string($payload)) {
            $decoded = json_decode($payload, true);
            if (!is_array($decoded)) {
                throw new TelegramException("Invalid JSON update payload: " . json_last_error_msg());
            }
            return new Update($decoded);
        }

        return new Update($payload);
    }

    private ?Router $router = null;

    /**
     * Gets or creates the internal Update Router.
     */
    public function router(): Router
    {
        return $this->router ??= new Router();
    }

    /**
     * Registers a command route (e.g. 'start', 'help').
     */
    public function onCommand(string $command, mixed $handler): static
    {
        $this->router()->onCommand($command, $handler);
        return $this;
    }

    /**
     * Registers a callback query route with pattern matching (exact, placeholder 'item:{id}', or regex).
     */
    public function onCallbackQuery(?string $pattern, mixed $handler): static
    {
        $this->router()->onCallbackQuery($pattern, $handler);
        return $this;
    }

    /**
     * Registers a message text route by pattern or regex.
     */
    public function onMessage(?string $pattern, mixed $handler): static
    {
        $this->router()->onMessage($pattern, $handler);
        return $this;
    }

    /**
     * Registers an inline query route.
     */
    public function onInlineQuery(?string $pattern, mixed $handler): static
    {
        $this->router()->onInlineQuery($pattern, $handler);
        return $this;
    }

    /**
     * Registers a route for any specific UpdateType.
     */
    public function on(UpdateType|string $type, mixed $handler): static
    {
        $this->router()->on($type, $handler);
        return $this;
    }

    /**
     * Registers a fallback route when no other route matches.
     */
    public function onFallback(mixed $handler): static
    {
        $this->router()->onFallback($handler);
        return $this;
    }

    /**
     * Registers an attribute-decorated controller class with #[OnCommand], #[OnCallbackQuery], etc.
     */
    public function registerController(string|object $controller): static
    {
        $this->router()->registerController($controller);
        return $this;
    }

    /**
     * Executes the bot with optional update handler(s) according to the configured running mode.
     *
     * Handlers receive:
     * - Parameter 1: Update $update (the incoming update)
     * - Parameter 2: Telegram $bot (this bot client instance)
     *
     * Handlers can be:
     * - Callables: fn(Update $update, Telegram $bot)
     * - Invokable class names: MyHandler::class
     * - Invokable class instances: new MyHandler()
     * - Arrays/lists of the above
     *
     * In WebhookMode: resolves the incoming update from webhook request, sets $this->update,
     * executes handler(s), and returns the resolved Update.
     * In PollingMode: enters an infinite long-polling loop, resolves incoming updates, sets $this->update,
     * and dispatches them to handler(s) (synchronously, in spawned child processes, or via custom dispatcher).
     *
     * @param mixed ...$handlers Handlers passed to run()
     * @return mixed
     */
    public function run(mixed ...$handlers): mixed
    {
        $allHandlers = [...$this->updateHandlers, ...$this->normalizeHandlers($handlers)];

        // Automatically dispatch to router if any routes have been registered
        if ($this->router !== null && $this->router->hasRoutes()) {
            $allHandlers[] = $this->router;
        }

        $dispatcher = function (Update $update) use ($allHandlers): mixed {
            $this->update = $update;

            // Prioritize active conversation Flow if running
            if ($this->flowManager()->handle($update, $this)) {
                return true;
            }

            $result = null;

            foreach ($allHandlers as $handler) {
                $result = $this->invokeHandler($handler, $update);
                if ($result === false) {
                    break;
                }
            }

            return $result;
        };

        return $this->getRunningMode()->processUpdate($this, !empty($allHandlers) ? $dispatcher : null);
    }

    /**
     * Invokes an individual update handler.
     *
     * @throws TelegramException
     */
    public function invokeHandler(mixed $handler, Update $update): mixed
    {
        if (is_string($handler) && class_exists($handler)) {
            $instance = $this->resolveHandlerInstance($handler);

            if (is_callable($instance)) {
                return $instance($update, $this);
            }

            throw new TelegramException("Handler class [{$handler}] must be invokable (missing __invoke method).");
        }

        if (is_callable($handler)) {
            return $handler($update, $this);
        }

        throw new TelegramException("Invalid update handler provided: expected callable or invokable class name, got " . get_debug_type($handler));
    }

    /**
     * Resolves an instance of a handler class, using the container if configured, or direct instantiation.
     */
    private function resolveHandlerInstance(string $className): object
    {
        $container = $this->container ?? $this->config->container;

        if ($container !== null) {
            if (is_object($container) && method_exists($container, 'get') && method_exists($container, 'has')) {
                if ($container->has($className)) {
                    return $container->get($className);
                }
            } elseif (is_callable($container)) {
                $resolved = $container($className);
                if (is_object($resolved)) {
                    return $resolved;
                }
            }
        }

        return new $className();
    }

    /**
     * Normalizes handlers passed as variadic arguments or arrays into a flat list.
     *
     * @param array<mixed> $handlers
     * @return list<mixed>
     */
    private function normalizeHandlers(array $handlers): array
    {
        $flat = [];
        foreach ($handlers as $handler) {
            if (is_array($handler)) {
                foreach ($this->normalizeHandlers($handler) as $h) {
                    $flat[] = $h;
                }
            } elseif ($handler !== null) {
                $flat[] = $handler;
            }
        }
        return $flat;
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

    #[\NoDiscard]
    public function getConfig(): Config
    {
        return $this->config;
    }
}
