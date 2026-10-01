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

        $coreDispatcher = function (Update $up, Telegram $b, Context $ctx) use ($allHandlers, $flowManager): mixed {
            $b->setUpdate($up);

            // 1. Prioritize active conversation Flow if running
            if ($flowManager !== null && $flowManager->handle($up, $b)) {
                return true;
            }

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

    public function invokeHandler(mixed $handler, Update $update, Telegram $bot, ?Context $context = null): mixed
    {
        $context ??= new Context($update, $bot->client, $bot);

        if (is_string($handler) && class_exists($handler)) {
            $instance = $this->resolveHandlerInstance($handler, $bot);

            if (is_callable($instance)) {
                return $this->callCallable($instance, $update, $bot, $context);
            }

            throw new TelegramException("Handler class [{$handler}] must be invokable (missing __invoke method).");
        }

        if (is_callable($handler)) {
            return $this->callCallable($handler, $update, $bot, $context);
        }

        throw new TelegramException("Invalid update handler provided: expected callable or invokable class name, got " . get_debug_type($handler));
    }

    private function callCallable(callable $callable, Update $update, Telegram $bot, Context $context): mixed
    {
        try {
            $ref = is_array($callable)
                ? new ReflectionMethod($callable[0], $callable[1])
                : new ReflectionFunction(Closure::fromCallable($callable));

            $params = $ref->getParameters();
            if (!empty($params)) {
                $firstParam = $params[0];
                $type = $firstParam->getType();

                if ($type instanceof ReflectionNamedType && $type->getName() === Context::class) {
                    return $callable($context, $bot);
                }
            }
        } catch (Throwable) {
            // Fallback to standard ($update, $bot) signature
        }

        return $callable($update, $bot);
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
