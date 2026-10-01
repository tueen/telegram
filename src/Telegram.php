<?php

declare(strict_types=1);

namespace Tueen\Telegram;

use Closure;
use Generator;
use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Client\RequestOptions;
use Tueen\Telegram\Client\Response;
use Tueen\Telegram\Client\TelegramClient;
use Tueen\Telegram\Context\ContextResolver;
use Tueen\Telegram\Dispatcher\UpdateDispatcher;
use Tueen\Telegram\Enums\UpdateType;
use Tueen\Telegram\Exceptions\TelegramException;
use Tueen\Telegram\Flow\Flow;
use Tueen\Telegram\Flow\FlowManager;
use Tueen\Telegram\Flow\FlowSession;
use Tueen\Telegram\Flow\Storage\StateStoreInterface;
use Tueen\Telegram\Formatting\Text;
use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Pipeline\MiddlewareInterface;
use Tueen\Telegram\Routing\Route;
use Tueen\Telegram\Routing\Router;
use Tueen\Telegram\Running\AutoMode;
use Tueen\Telegram\Running\PollingMode;
use Tueen\Telegram\Running\RunningModeInterface;
use Tueen\Telegram\Running\WebhookMode;
use Tueen\Telegram\Testing\TelegramFake;
use Tueen\Telegram\Types\Chat;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Types\Error;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Types\Update;
use Tueen\Telegram\Types\User;

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
     * Shorthand alias for {@see self::BOT_API_VERSION}.
     *
     * @see self::BOT_API_VERSION
     */
    public const string API_VERSION = self::BOT_API_VERSION;

    /**
     * Current resolved Update instance.
     * Defaults to null until resolved by Webhook or Polling runner.
     */
    public ?Update $update = null {
        set {
            $this->update = $value;
            if (isset($this->context)) {
                $this->context->update = $value;
            }
        }
    }

    /**
     * Contextual parameter resolver for auto-injecting update values.
     */
    private(set) ContextResolver $context;

    /**
     * Pure Telegram Bot API client.
     */
    private(set) TelegramClient $client;

    /**
     * Incoming update pipeline & exception dispatcher.
     */
    private(set) UpdateDispatcher $dispatcher;

    private(set) Config $config;
    private ?RunningModeInterface $_runningMode = null;
    private mixed $_container = null;
    protected ?FlowManager $_flowManager = null;
    protected ?Router $_router = null;

    public RunningModeInterface $runningMode {
        get => $this->_runningMode ??= new WebhookMode();
        set (RunningModeInterface $mode) => $this->_runningMode = $mode;
    }

    public mixed $container {
        get => $this->_container ?? $this->config->container;
        set {
            $this->_container = $value;
            if (isset($this->dispatcher)) {
                $this->dispatcher->container = $value;
            }
        }
    }

    public FlowManager $flowManager {
        get => $this->_flowManager ??= new FlowManager();
        set (FlowManager $manager) => $this->_flowManager = $manager;
    }

    public Router $router {
        get => $this->_router ??= new Router($this);
        set (Router $router) {
            $router->bot = $this;
            $this->_router = $router;
        }
    }

    public ?int $chatId {
        get => $this->context->resolveChatId();
    }

    public ?int $userId {
        get => $this->context->resolveUserId();
    }

    public ?int $messageId {
        get => $this->context->resolveMessageId();
    }

    public ?string $businessConnectionId {
        get => $this->context->resolveBusinessConnectionId();
    }

    public ?int $messageThreadId {
        get => $this->context->resolveMessageThreadId();
    }

    public ?string $inlineMessageId {
        get => $this->context->resolveInlineMessageId();
    }

    public ?string $callbackQueryId {
        get => $this->context->resolveCallbackQueryId();
    }

    public ?string $inlineQueryId {
        get => $this->context->resolveInlineQueryId();
    }

    public ?string $shippingQueryId {
        get => $this->context->resolveShippingQueryId();
    }

    public ?string $preCheckoutQueryId {
        get => $this->context->resolvePreCheckoutQueryId();
    }

    public ?int $directMessagesTopicId {
        get => $this->context->resolveDirectMessagesTopicId();
    }

    public ?string $guestQueryId {
        get => $this->context->resolveGuestQueryId();
    }

    public ?User $user {
        get => $this->update?->findUser();
    }

    public ?Chat $chat {
        get => $this->update?->findChat();
    }

    public ?Message $message {
        get => $this->update?->findMessage();
    }

    public function __construct(string|Config $tokenOrConfig)
    {
        if (is_string($tokenOrConfig)) {
            $this->config = new Config(botToken: $tokenOrConfig);
        } else {
            $this->config = $tokenOrConfig;
        }

        $this->context = new ContextResolver($this->update);
        $this->client = new TelegramClient($this->config, $this->context);
        $this->container = $this->config->container;
        $this->dispatcher = new UpdateDispatcher($this->container);
        if ($this->config->runningMode !== null) {
            $this->runningMode = $this->config->runningMode;
        }

        if ($this->config->rootFlow !== null) {
            $this->setRootFlow($this->config->rootFlow);
        }
        if (!empty($this->config->flowAllowedUpdates)) {
            $this->flowManager->setDefaultAllowedUpdates($this->config->flowAllowedUpdates);
        }
    }

    public function __clone()
    {
        if (isset($this->context)) {
            $this->context = clone $this->context;
        }
        if (isset($this->client)) {
            $this->client = clone $this->client;
            if (isset($this->context)) {
                $this->client->contextResolver = $this->context;
            }
        }
    }

    /**
     * Creates an isolated, thread-safe scoped client instance bound to a specific Update.
     * Prevents race conditions and state leakage across concurrent requests in long-running runtimes.
     */
    public function scoped(?Update $update = null): static
    {
        $scoped = clone $this;
        $scoped->update = $update;
        return $scoped;
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
        $this->client->pipe($middleware);
        return $this;
    }

    /**
     * Register a callback to be called before sending a request.
     *
     * @param callable(Request, Config): void $callback
     */
    public function onBeforeRequest(callable $callback): static
    {
        $this->client->onBeforeRequest($callback);
        return $this;
    }

    /**
     * Register a callback to be called after a raw HTTP response is received.
     *
     * @param callable(Response, Request): void $callback
     */
    public function onAfterRequest(callable $callback): static
    {
        $this->client->onAfterRequest($callback);
        return $this;
    }

    /**
     * Register a callback to be called when an error occurs.
     *
     * @param callable(\Throwable, Request): void $callback
     */
    public function onError(callable $callback): static
    {
        $this->client->onError($callback);
        return $this;
    }

    /**
     * Register a callback to be called when a final response (Type or Error) is produced.
     *
     * @param callable(Type, Request): void $callback
     */
    public function onResponse(callable $callback): static
    {
        $this->client->onResponse($callback);
        return $this;
    }

    /**
     * Registers an exception handler for incoming update processing errors.
     *
     * @param class-string<\Throwable>|callable(\Throwable, Update, Telegram): mixed $exceptionOrHandler
     * @param (callable(\Throwable, Update, Telegram): mixed)|null $handler
     * @return static
     */
    public function catch(string|callable $exceptionOrHandler, ?callable $handler = null): static
    {
        $this->dispatcher->catch($exceptionOrHandler, $handler);
        return $this;
    }

    /**
     * Convenience shorthand alias for {@see catch()} with a universal \Throwable handler.
     *
     * @param callable(\Throwable, Update, Telegram): mixed $handler
     * @return static
     * @see catch()
     */
    public function onUpdateError(callable $handler): static
    {
        return $this->catch($handler);
    }

    /**
     * Handles an exception thrown during incoming update processing.
     */
    public function handleUpdateException(\Throwable $e, Update $update): bool
    {
        return $this->dispatcher->handleException($e, $update, $this);
    }

    public function send(
        Method $method,
        ?Closure $uploadProgress = null,
        ?Closure $downloadProgress = null,
        ?RequestOptions $options = null
    ): mixed {
        return $this->client->send($method, $uploadProgress, $downloadProgress, $options);
    }

    /**
     * Dynamic method invocation for any Telegram Bot API method.
     */
    public function __call(string $name, array $arguments): mixed
    {
        return $this->client->__call($name, $arguments);
    }

    /**
     * Downloads a file from Telegram.
     */
    public function downloadFile(mixed $file, mixed $destination, ?callable $progress = null): BooleanResult|Error
    {
        return $this->client->downloadFile($file, $destination, $progress);
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
     * Configures the client to use adaptive AutoMode (switches between Polling in CLI and Webhook in HTTP).
     */
    public function useAutoMode(
        ?PollingMode $polling = null,
        ?WebhookMode $webhook = null,
        bool $autoDeleteWebhook = false,
        bool $dropPendingUpdatesOnDelete = false,
        ?callable $detector = null,
    ): static {
        $this->runningMode = new AutoMode(
            pollingMode: $polling,
            webhookMode: $webhook,
            autoDeleteWebhook: $autoDeleteWebhook,
            dropPendingUpdatesOnDelete: $dropPendingUpdatesOnDelete,
            detector: $detector
        );
        return $this;
    }

    /**
     * Manually updates the active Update instance and context.
     */
    public function setUpdate(?Update $update): static
    {
        $this->update = $update;
        return $this;
    }

    /**
     * Quick reply helper to send a text message to the active chat in context.
     *
     * @param string|Text $text
     */
    public function reply(string|Text $text, mixed ...$args): mixed
    {
        $params = ['text' => $text];
        foreach ($args as $k => $v) {
            if (is_array($v) && is_int($k)) {
                $params = array_merge($params, $v);
            } else {
                $params[$k] = $v;
            }
        }

        return $this->sendMessage($params);
    }

    /**
     * Sends a long text message split safely into sequential messages adhering to Telegram limits.
     *
     * @param string|Text $text
     * @param int $chunkSize Maximum character length per chunk (default: 4096)
     * @param int|string|null $chatId Target chat ID (optional if active in context)
     * @return list<mixed>
     */
    public function sendMessageChunked(string|Text $text, int $chunkSize = 4096, int|string|null $chatId = null, mixed ...$args): array
    {
        $rawText = (string)$text;
        $chunks = Text::chunk($rawText, $chunkSize);
        $sent = [];

        foreach ($chunks as $chunk) {
            $params = ['text' => $chunk];
            if ($chatId !== null) {
                $params['chatId'] = $chatId;
            }
            foreach ($args as $k => $v) {
                if (is_array($v) && is_int($k)) {
                    $params = array_merge($params, $v);
                } else {
                    $params[$k] = $v;
                }
            }
            if ($text instanceof Text && !isset($params['parseMode']) && !isset($params['parse_mode'])) {
                $params['parseMode'] = $text->parseMode;
            }
            $sent[] = $this->sendMessage($params);
        }

        return $sent;
    }

    /**
     * Replies with a long text message split safely into sequential messages.
     *
     * @param string|Text $text
     * @param int $chunkSize
     * @return list<mixed>
     */
    public function replyChunked(string|Text $text, int $chunkSize = 4096, mixed ...$args): array
    {
        return $this->sendMessageChunked($text, $chunkSize, null, ...$args);
    }

    public function bindDefault(string $param, callable $resolver): static
    {
        $this->context->bind($param, $resolver);
        return $this;
    }

    public function setContainer(mixed $container): static
    {
        $this->container = $container;
        return $this;
    }

    public function getContainer(): mixed
    {
        return $this->container;
    }

    public function setFlowManager(FlowManager $manager): static
    {
        $this->flowManager = $manager;
        return $this;
    }

    public function setFlowStore(StateStoreInterface $store): static
    {
        $this->flowManager->store = $store;
        return $this;
    }

    public function setRootFlow(?string $flowClass): static
    {
        $this->flowManager->rootFlow = $flowClass;
        return $this;
    }

    public function startFlow(
        string $flowClass,
        ?Update $update = null,
        string $initialStep = 'start',
        array $initialData = []
    ): Flow {
        $resolvedUpdate = $update ?? $this->update;
        if ($resolvedUpdate === null) {
            throw new TelegramException("Cannot start Flow: no active Update found. Pass Update explicitly or run inside an update handler.");
        }

        return $this->flowManager->startFlow(
            flowClass: $flowClass,
            update: $resolvedUpdate,
            bot: $this,
            initialStep: $initialStep,
            initialData: $initialData
        );
    }

    public function flow(int|string|null $chatId = null, ?int $userId = null, ?Update $update = null): FlowSession
    {
        [$resolvedChatId, $resolvedUserId] = $this->resolveSessionChatAndUser($chatId, $userId, $update);
        if ($resolvedChatId === null) {
            throw new TelegramException("Cannot resolve Flow session: no chat ID available.");
        }

        return $this->flowManager->flowSession($resolvedChatId, $resolvedUserId, $this, $update ?? $this->update);
    }

    public function hasActiveFlow(int|string|null $chatId = null, ?int $userId = null, ?Update $update = null): bool
    {
        [$resolvedChatId, $resolvedUserId] = $this->resolveSessionChatAndUser($chatId, $userId, $update);
        if ($resolvedChatId === null) {
            return false;
        }

        return $this->flowManager->hasActiveFlow($resolvedChatId, $resolvedUserId);
    }

    public function getActiveFlowClass(int|string|null $chatId = null, ?int $userId = null, ?Update $update = null): ?string
    {
        [$resolvedChatId, $resolvedUserId] = $this->resolveSessionChatAndUser($chatId, $userId, $update);
        if ($resolvedChatId === null) {
            return null;
        }

        return $this->flowManager->getActiveFlowClass($resolvedChatId, $resolvedUserId);
    }

    public function getActiveFlow(int|string|null $chatId = null, ?int $userId = null, ?Update $update = null): ?Flow
    {
        [$resolvedChatId, $resolvedUserId] = $this->resolveSessionChatAndUser($chatId, $userId, $update);
        if ($resolvedChatId === null) {
            return null;
        }

        return $this->flowManager->getActiveFlowInstance($resolvedChatId, $resolvedUserId, $this, $update ?? $this->update);
    }

    public function flowBack(int|string|null $chatId = null, ?int $userId = null, ?string $replyMessage = null, ?Update $update = null): bool
    {
        [$resolvedChatId, $resolvedUserId] = $this->resolveSessionChatAndUser($chatId, $userId, $update);
        if ($resolvedChatId === null) {
            return false;
        }

        return $this->flowManager->navigateBack($resolvedChatId, $resolvedUserId, $this, $replyMessage, $update ?? $this->update);
    }

    public function cancelFlow(int|string|null $chatId = null, ?int $userId = null, ?string $replyMessage = 'Operation cancelled.', ?Update $update = null): bool
    {
        [$resolvedChatId, $resolvedUserId] = $this->resolveSessionChatAndUser($chatId, $userId, $update);
        if ($resolvedChatId === null) {
            return false;
        }

        return $this->flowManager->cancelFlow($resolvedChatId, $resolvedUserId, $this, $replyMessage, $update ?? $this->update);
    }

    public function finishFlow(int|string|null $chatId = null, ?int $userId = null, ?Update $update = null): bool
    {
        [$resolvedChatId, $resolvedUserId] = $this->resolveSessionChatAndUser($chatId, $userId, $update);
        if ($resolvedChatId === null) {
            return false;
        }

        return $this->flowManager->finishFlow($resolvedChatId, $resolvedUserId, $this, $update ?? $this->update);
    }

    private function resolveSessionChatAndUser(int|string|null $chatId = null, ?int $userId = null, ?Update $update = null): array
    {
        if ($chatId !== null) {
            return [$chatId, $userId];
        }

        $resolvedUpdate = $update ?? $this->update;
        $chat = $resolvedUpdate?->findChat();
        $user = $resolvedUpdate?->findUser();

        $resolvedChatId = $chat?->id ?? $this->context->resolveChatId();
        $resolvedUserId = $userId ?? $user?->id ?? $this->context->resolveUserId();

        return [$resolvedChatId, $resolvedUserId];
    }

    public function handle(mixed ...$handlers): static
    {
        $this->dispatcher->addHandler(...$handlers);
        return $this;
    }

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

    /**
     * Registers a command route handler (e.g. '/start', '/help').
     *
     * @param string $command Command name with or without leading slash
     * @param mixed $handler Closure, callable, or [ControllerClass, 'method']
     * @param bool $priority When true, allows this route to execute even if a Flow is active
     * @return static
     */
    public function onCommand(string $command, mixed $handler, bool $priority = false): Route
    {
        $route = $this->router->onCommand($command, $handler, $priority);
        $route->bot = $this;
        return $route;
    }

    /**
     * Registers a callback query route handler matching an optional regex pattern.
     *
     * @param string|null $pattern Regex pattern to match against callback_data, or null for any
     * @param mixed $handler Closure, callable, or [ControllerClass, 'method']
     * @param bool $priority When true, allows this route to execute even if a Flow is active
     * @return Route
     */
    public function onCallbackQuery(?string $pattern, mixed $handler, bool $priority = false): Route
    {
        $route = $this->router->onCallbackQuery($pattern, $handler, $priority);
        $route->bot = $this;
        return $route;
    }

    /**
     * Registers a message route handler matching an optional regex pattern against message text/caption.
     *
     * @param string|null $pattern Regex pattern to match against text, or null for any message
     * @param mixed $handler Closure, callable, or [ControllerClass, 'method']
     * @param bool $priority When true, allows this route to execute even if a Flow is active
     * @return Route
     */
    public function onMessage(?string $pattern, mixed $handler, bool $priority = false): Route
    {
        $route = $this->router->onMessage($pattern, $handler, $priority);
        $route->bot = $this;
        return $route;
    }

    /**
     * Convenience alias for onMessage.
     */
    public function onText(?string $pattern, mixed $handler, bool $priority = false): Route
    {
        return $this->onMessage($pattern, $handler, $priority);
    }

    /**
     * Registers an inline query route handler matching an optional regex pattern against query text.
     *
     * @param string|null $pattern Regex pattern to match against query text, or null for any
     * @param mixed $handler Closure, callable, or [ControllerClass, 'method']
     * @param bool $priority When true, allows this route to execute even if a Flow is active
     * @return Route
     */
    public function onInlineQuery(?string $pattern, mixed $handler, bool $priority = false): Route
    {
        $route = $this->router->onInlineQuery($pattern, $handler, $priority);
        $route->bot = $this;
        return $route;
    }

    /**
     * Registers an update route handler for a specific update type.
     *
     * @param UpdateType|string $type Telegram update type enum or string (e.g. 'message', 'chat_member')
     * @param mixed $handler Closure, callable, or [ControllerClass, 'method']
     * @param bool $priority When true, allows this route to execute even if a Flow is active
     * @return static
     */
    public function on(UpdateType|string $type, mixed $handler, bool $priority = false): static
    {
        $this->router->on($type, $handler, $priority);
        return $this;
    }

    /**
     * Groups related routes with shared attributes (prefix, middleware, chat_type).
     *
     * @param array<string, mixed>|callable $attributesOrCallback
     * @param (callable(Telegram): void)|null $callback
     */
    public function group(array|callable $attributesOrCallback, ?callable $callback = null): static
    {
        $actualCallback = is_callable($attributesOrCallback) ? $attributesOrCallback : $callback;
        $attributes = is_array($attributesOrCallback) ? $attributesOrCallback : [];

        $this->router->group($attributes, function () use ($actualCallback) {
            if ($actualCallback !== null) {
                $actualCallback($this);
            }
        });

        return $this;
    }

    /**
     * Registers a fallback route handler when no other routes or flows match the incoming update.
     *
     * @param mixed $handler Closure, callable, or [ControllerClass, 'method']
     * @return static
     */
    public function onFallback(mixed $handler): static
    {
        $this->router->onFallback($handler);
        return $this;
    }

    /**
     * Registers an attribute-annotated controller class or instance.
     *
     * @param string|object $controller Class name or object instance decorated with #[OnCommand], etc.
     * @return static
     */
    public function registerController(string|object $controller): static
    {
        $this->router->registerController($controller);
        return $this;
    }

    /**
     * Registers a global update middleware into the dispatch pipeline.
     *
     * @param callable(Update, callable(Update): mixed, Telegram): mixed $middleware
     * @return static
     */
    public function middleware(callable $middleware): static
    {
        $this->dispatcher->middleware($middleware);
        return $this;
    }

    /**
     * Expressive shorthand alias for {@see middleware()} (popularized by Telegraf/grammY conventions).
     *
     * @param callable(Update, callable(Update): mixed, Telegram): mixed $middleware
     * @return static
     * @see middleware()
     */
    public function use(callable $middleware): static
    {
        return $this->middleware($middleware);
    }

    /**
     * Executes the bot using the configured running mode (WebhookMode or PollingMode).
     *
     * @param mixed ...$handlers Optional handlers, closures, or controller instances
     * @return mixed Running mode execution result or response
     */
    public function run(mixed ...$handlers): mixed
    {
        $hasHandlers = !empty($this->dispatcher->handlers)
            || !empty($handlers)
            || ($this->router !== null && $this->router->hasRoutes());

        $handlerCallback = $hasHandlers
            ? fn(Update $update) => $this->dispatcher->dispatch($update, $this, $handlers, $this->router, $this->flowManager)
            : null;

        return $this->runningMode->processUpdate($this, $handlerCallback);
    }

    /**
     * Executes the bot with automatic runtime detection (AutoMode).
     * Automatically uses Polling in CLI environments and Webhook in HTTP server environments.
     *
     * @param mixed ...$handlers Optional handlers, closures, or controller instances
     * @return mixed Running mode execution result or response
     */
    public function autoRun(mixed ...$handlers): mixed
    {
        if (!$this->runningMode instanceof AutoMode) {
            $this->useAutoMode();
        }

        return $this->run(...$handlers);
    }

    /**
     * Resolves dependencies and invokes a single update handler.
     *
     * @param mixed $handler Handler callable, closure, or [Class, 'method']
     * @param Update $update The active Telegram update
     * @return mixed
     */
    public function invokeHandler(mixed $handler, Update $update): mixed
    {
        return $this->dispatcher->invokeHandler($handler, $update, $this);
    }

    /**
     * Returns a lazy Generator that long-polls Telegram Bot API for incoming updates.
     *
     * @param int $timeout Polling timeout in seconds
     * @param int $limit Maximum updates to fetch per request (1-100)
     * @param array<string>|null $allowedUpdates List of update types to receive
     * @return Generator<int, Update>
     */
    public function poll(int $timeout = 30, int $limit = 100, ?array $allowedUpdates = null): Generator
    {
        $mode = new PollingMode(timeout: $timeout, limit: $limit, allowedUpdates: $allowedUpdates);
        return $mode->getUpdatesGenerator($this);
    }
}
