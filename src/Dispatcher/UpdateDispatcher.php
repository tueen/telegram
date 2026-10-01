<?php

declare(strict_types=1);

namespace Tueen\Telegram\Dispatcher;

use Closure;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionNamedType;
use Throwable;
use Tueen\Telegram\Context\Context;
use Tueen\Telegram\Exceptions\TelegramException;
use Tueen\Telegram\Flow\FlowManager;
use Tueen\Telegram\Routing\Router;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Chat;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\User;
use Tueen\Telegram\Types\Update;

/**
 * Dispatches incoming Telegram updates through the middleware pipeline,
 * active conversation flows, routers, and update handlers.
 */
class UpdateDispatcher
{
    /** @var list<mixed> */
    private(set) array $handlers = [];

    /** @var list<callable> */
    private array $middlewares = [];

    /** @var list<array{type: class-string<Throwable>|null, handler: callable}> */
    private array $exceptionHandlers = [];

    /** @var array<string, list<array{name: string, type: ?string, isBuiltin: bool, allowsNull: bool, hasDefault: bool, default: mixed}>> */
    private static array $callableMetaCache = [];

    public function __construct(
        public mixed $container = null
    ) {}

    public function setContainer(mixed $container): static
    {
        $this->container = $container;
        return $this;
    }

    public function addHandler(mixed ...$handlers): static
    {
        foreach ($this->normalizeHandlers($handlers) as $h) {
            $this->handlers[] = $h;
        }
        return $this;
    }

    public function middleware(callable $middleware): static
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    public function catch(string|callable $exceptionOrHandler, ?callable $handler = null): static
    {
        if (is_callable($exceptionOrHandler) && $handler === null) {
            $this->exceptionHandlers[] = [
                'type' => null,
                'handler' => $exceptionOrHandler,
            ];
            return $this;
        }

        if (is_string($exceptionOrHandler) && $handler !== null) {
            $this->exceptionHandlers[] = [
                'type' => $exceptionOrHandler,
                'handler' => $handler,
            ];
            return $this;
        }

        throw new \InvalidArgumentException("Invalid catch handler signature. Provide either a callable or an exception class string and callable.");
    }

    public function handleException(Throwable $e, Update $update, Telegram $bot): bool
    {
        $handled = false;

        foreach ($this->exceptionHandlers as $item) {
            $type = $item['type'];
            $handler = $item['handler'];

            if ($type === null || $e instanceof $type) {
                $handler($e, $update, $bot);
                $handled = true;
            }
        }

        if (!$handled && $bot->config->logger !== null) {
            $bot->config->logger->error("Uncaught update processing exception: " . $e->getMessage(), [
                'exception' => $e,
                'update_id' => $update->updateId,
                'update_type' => $update->type->value,
            ]);
        }

        return $handled;
    }

    /**
     * Dispatches an incoming update through middlewares, active flow, and handlers/router.
     */
    public function dispatch(
        Update $update,
        Telegram $bot,
        array $extraHandlers = [],
        ?Router $router = null,
        ?FlowManager $flowManager = null
    ): mixed {
        $bot->setUpdate($update);
        $context = new Context($update, $bot->client, $bot);

        $allHandlers = [...$this->handlers, ...$this->normalizeHandlers($extraHandlers)];

        if ($router !== null && $router->hasRoutes()) {
            $allHandlers[] = $router;
        }

        $coreDispatcher = function (Update $up, Telegram $b, Context $ctx) use ($allHandlers, $router, $flowManager): mixed {
            $b->setUpdate($up);

            // 1. High-priority routes evaluation (runs BEFORE active conversation flow)
            if ($router !== null && $router->hasRoutes()) {
                $parameters = [];
                $priorityRoute = $router->findMatchingRoute($up, $b, priorityOnly: true, parameters: $parameters);

                if ($priorityRoute !== null) {
                    $shouldExecute = true;

                    // If user is inside an active flow, allow the flow to inspect, customize, or veto
                    if ($flowManager !== null) {
                        $activeFlow = $flowManager->getActiveFlow($up, $b);
                        if ($activeFlow !== null) {
                            $shouldExecute = $activeFlow->allowsPriorityRoute($priorityRoute, $up);
                            if ($shouldExecute) {
                                $activeFlow->onPriorityRoute($priorityRoute, $up);
                            }
                        }
                    }

                    if ($shouldExecute) {
                        return $router->dispatchRoute($priorityRoute, $up, $b, $parameters);
                    }
                }
            }

            // 2. Active conversation Flow takes precedence over standard routes
            if ($flowManager !== null && $flowManager->handle($up, $b)) {
                return true;
            }

            // 3. Fallthrough to regular handlers & routes
            $result = null;

            foreach ($allHandlers as $handler) {
                $result = $this->invokeHandler($handler, $up, $b, $ctx);
                if ($result === false) {
                    break;
                }
            }

            return $result;
        };

        try {
            if (empty($this->middlewares)) {
                return $coreDispatcher($update, $bot, $context);
            }

            $pipeline = array_reduce(
                array_reverse($this->middlewares),
                function (callable $next, callable $middleware) {
                    return function (Update $up, Telegram $b, Context $ctx) use ($middleware, $next) {
                        return $middleware($up, $b, fn(Update $u, Telegram $botInst) => $next($u, $botInst, $ctx));
                    };
                },
                fn(Update $up, Telegram $b, Context $ctx) => $coreDispatcher($up, $b, $ctx)
            );

            return $pipeline($update, $bot, $context);
        } catch (Throwable $e) {
            if ($this->handleException($e, $update, $bot)) {
                return null;
            }

            throw $e;
        }
    }

    public function invokeHandler(mixed $handler, Update $update, Telegram $bot, ?Context $context = null, array $extraParameters = []): mixed
    {
        $context ??= new Context($update, $bot->client, $bot);

        if ($handler instanceof Router) {
            return $handler->dispatch($update, $bot, checkFlow: false);
        }

        if (is_string($handler) && class_exists($handler)) {
            $instance = $this->resolveHandlerInstance($handler, $bot);

            if (is_callable($instance)) {
                return $this->callCallable($instance, $update, $bot, $context, $extraParameters);
            }

            throw new TelegramException("Handler class [{$handler}] must be invokable (missing __invoke method).");
        }

        if (is_callable($handler)) {
            return $this->callCallable($handler, $update, $bot, $context, $extraParameters);
        }

        throw new TelegramException("Invalid update handler provided: expected callable or invokable class name, got " . get_debug_type($handler));
    }

    private function resolveCallableParameters(
        callable $callable,
        Update $update,
        Telegram $bot,
        Context $context,
        array $extraParameters = []
    ): array {
        $cacheKey = null;
        if (is_array($callable) && is_object($callable[0])) {
            $cacheKey = $callable[0]::class . '::' . $callable[1];
        } elseif (is_array($callable) && is_string($callable[0])) {
            $cacheKey = $callable[0] . '::' . $callable[1];
        } elseif (is_string($callable)) {
            $cacheKey = $callable;
        }

        $meta = null;
        if ($cacheKey !== null && isset(self::$callableMetaCache[$cacheKey])) {
            $meta = self::$callableMetaCache[$cacheKey];
        } else {
            try {
                $ref = is_array($callable)
                    ? new ReflectionMethod($callable[0], $callable[1])
                    : new ReflectionFunction(Closure::fromCallable($callable));

                $params = $ref->getParameters();
                $meta = [];
                foreach ($params as $param) {
                    $type = $param->getType();
                    $typeName = null;
                    $isBuiltin = false;
                    $allowsNull = $param->allowsNull();

                    if ($type instanceof ReflectionNamedType) {
                        $typeName = $type->getName();
                        $isBuiltin = $type->isBuiltin();
                    }

                    $meta[] = [
                        'name' => $param->getName(),
                        'type' => $typeName,
                        'isBuiltin' => $isBuiltin,
                        'allowsNull' => $allowsNull,
                        'hasDefault' => $param->isDefaultValueAvailable(),
                        'default' => $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null,
                    ];
                }

                if ($cacheKey !== null) {
                    self::$callableMetaCache[$cacheKey] = $meta;
                }
            } catch (Throwable) {
                return [$update, $bot, ...$extraParameters];
            }
        }

        if (empty($meta)) {
            return [];
        }

        $container = $this->container ?? $bot->container;
        $resolved = [];
        $remainingExtra = $extraParameters;

        foreach ($meta as $info) {
            $pName = $info['name'];
            $pType = $info['type'];

            // 1. By type-hint
            if ($pType !== null) {
                if ($pType === Context::class || is_subclass_of($pType, Context::class)) {
                    $resolved[] = $context;
                    continue;
                }
                if ($pType === Update::class || is_subclass_of($pType, Update::class)) {
                    $resolved[] = $update;
                    continue;
                }
                if ($pType === Telegram::class || is_subclass_of($pType, Telegram::class)) {
                    $resolved[] = $bot;
                    continue;
                }
                if ($pType === User::class || is_subclass_of($pType, User::class)) {
                    $resolved[] = $update->findUser();
                    continue;
                }
                if ($pType === Chat::class || is_subclass_of($pType, Chat::class)) {
                    $resolved[] = $update->findChat();
                    continue;
                }
                if ($pType === Message::class || is_subclass_of($pType, Message::class)) {
                    $resolved[] = $update->findMessage();
                    continue;
                }

                // Check container for custom service classes
                if (!$info['isBuiltin'] && $container !== null) {
                    if (is_object($container) && method_exists($container, 'has') && $container->has($pType)) {
                        $resolved[] = $container->get($pType);
                        continue;
                    }
                    if (is_callable($container)) {
                        $svc = $container($pType);
                        if (is_object($svc)) {
                            $resolved[] = $svc;
                            continue;
                        }
                    }
                }
            }

            // 2. By parameter name in extraParameters (route placeholder values)
            if (array_key_exists($pName, $remainingExtra)) {
                $rawVal = $remainingExtra[$pName];
                unset($remainingExtra[$pName]);

                if ($pType === 'int') {
                    $resolved[] = (int)$rawVal;
                } elseif ($pType === 'float') {
                    $resolved[] = (float)$rawVal;
                } elseif ($pType === 'bool') {
                    $resolved[] = filter_var($rawVal, FILTER_VALIDATE_BOOLEAN);
                } else {
                    $resolved[] = $rawVal;
                }
                continue;
            }

            // 3. By conventional parameter names
            if ($pName === 'context' || $pName === 'ctx') {
                $resolved[] = $context;
                continue;
            }
            if ($pName === 'update' || $pName === 'u') {
                $resolved[] = $update;
                continue;
            }
            if ($pName === 'bot' || $pName === 'b' || $pName === 'telegram') {
                $resolved[] = $bot;
                continue;
            }
            if ($pName === 'user') {
                $resolved[] = $update->findUser();
                continue;
            }
            if ($pName === 'chat') {
                $resolved[] = $update->findChat();
                continue;
            }
            if ($pName === 'message' || $pName === 'msg') {
                $resolved[] = $update->findMessage();
                continue;
            }

            // 4. Default value
            if ($info['hasDefault']) {
                $resolved[] = $info['default'];
                continue;
            }

            // 5. Nullable fallback
            if ($info['allowsNull']) {
                $resolved[] = null;
                continue;
            }

            // Fallback: pass next remaining extra parameter or null
            $resolved[] = !empty($remainingExtra) ? array_shift($remainingExtra) : null;
        }

        return $resolved;
    }

    public function callCallable(callable $callable, Update $update, Telegram $bot, Context $context, array $extraParameters = []): mixed
    {
        $args = $this->resolveCallableParameters($callable, $update, $bot, $context, $extraParameters);
        return $callable(...$args);
    }

    private function resolveHandlerInstance(string $className, Telegram $bot): object
    {
        $container = $this->container ?? $bot->container;

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

    public function normalizeHandlers(array $handlers): array
    {
        $flat = [];
        foreach ($handlers as $handler) {
            if (is_array($handler) && !is_callable($handler)) {
                foreach ($this->normalizeHandlers($handler) as $h) {
                    $flat[] = $h;
                }
            } elseif ($handler !== null) {
                $flat[] = $handler;
            }
        }
        return $flat;
    }
}
