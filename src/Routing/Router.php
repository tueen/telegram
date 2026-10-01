<?php

declare(strict_types=1);

namespace Tueen\Telegram\Routing;

use ReflectionClass;
use ReflectionMethod;
use Tueen\Telegram\Enums\UpdateType;
use Tueen\Telegram\Exceptions\TelegramException;
use Tueen\Telegram\Routing\Attributes\OnCallbackQuery;
use Tueen\Telegram\Routing\Attributes\OnCommand;
use Tueen\Telegram\Routing\Attributes\OnInlineQuery;
use Tueen\Telegram\Routing\Attributes\OnMessage;
use Tueen\Telegram\Routing\Attributes\OnUpdate;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

/**
 * Modern declarative and fluent update router for tueen/telegram.
 */
class Router
{
    /** @var list<Route> */
    private array $routes = [];

    /** @var list<array<string, mixed>> */
    private array $groupStack = [];

    private mixed $fallbackHandler = null;

    public ?Telegram $bot = null;

    public function __construct(?Telegram $bot = null)
    {
        $this->bot = $bot;
    }

    /**
     * Groups related routes with shared attributes (prefix, middleware, chat_type).
     *
     * @param array<string, mixed>|callable $attributesOrCallback
     * @param (callable(Router): void)|null $callback
     */
    public function group(array|callable $attributesOrCallback, ?callable $callback = null): static
    {
        if (is_callable($attributesOrCallback) && $callback === null) {
            $attributes = [];
            $cb = $attributesOrCallback;
        } else {
            $attributes = is_array($attributesOrCallback) ? $attributesOrCallback : [];
            $cb = $callback;
        }

        $this->groupStack[] = $attributes;

        try {
            if ($cb !== null) {
                $cb($this);
            }
        } finally {
            array_pop($this->groupStack);
        }

        return $this;
    }

    /**
     * Adds and registers a Route, automatically applying active group attributes.
     */
    public function addRoute(Route $route): Route
    {
        if (!empty($this->groupStack)) {
            $combinedPrefix = '';
            $combinedMiddlewares = [];
            $combinedChatType = null;

            foreach ($this->groupStack as $group) {
                if (!empty($group['prefix'])) {
                    $combinedPrefix .= $group['prefix'];
                }
                if (!empty($group['middleware'])) {
                    $mws = is_array($group['middleware']) ? $group['middleware'] : [$group['middleware']];
                    foreach ($mws as $mw) {
                        $combinedMiddlewares[] = $mw;
                    }
                }
                if (!empty($group['chat_type'])) {
                    $combinedChatType = (string)$group['chat_type'];
                }
            }

            if ($combinedPrefix !== '' && $route->pattern !== null) {
                if ($route->isCommand()) {
                    $cleanPrefix = ltrim($combinedPrefix, '/');
                    if (!str_ends_with($cleanPrefix, '_') && !str_ends_with($cleanPrefix, ' ') && $cleanPrefix !== '') {
                        $cleanPrefix .= '_';
                    }
                    $cleanPrefix = rtrim($cleanPrefix, ' ');
                    $newPattern = $cleanPrefix . ltrim($route->pattern, '/');
                } else {
                    $newPattern = $combinedPrefix . $route->pattern;
                }

                $route = new Route(
                    type: $route->typeString,
                    pattern: $newPattern,
                    handler: $route->handler,
                    isCommand: $route->isCommand(),
                    isPriority: $route->isPriority()
                );
            }

            if (!empty($combinedMiddlewares)) {
                $route->middleware(...$combinedMiddlewares);
            }

            if ($combinedChatType !== null && $route->chatType === null) {
                $route->filterChatType($combinedChatType);
            }
        }

        $route->bot = $this->bot;
        $this->routes[] = $route;
        return $route;
    }

    /**
     * Registers a bot command route (e.g. 'start', '/help').
     * Set $priority to true to allow this route to execute even if an active Flow is running.
     */
    public function onCommand(string $command, mixed $handler, bool $priority = false): Route
    {
        return $this->addRoute(new Route(
            type: UpdateType::MESSAGE,
            pattern: ltrim($command, '/'),
            handler: $handler,
            isCommand: true,
            isPriority: $priority
        ));
    }

    /**
     * Registers a callback query route by exact match, placeholder ('item:{id}'), or regex.
     * Set $priority to true to allow this route to execute even if an active Flow is running.
     */
    public function onCallbackQuery(?string $pattern, mixed $handler, bool $priority = false): Route
    {
        return $this->addRoute(new Route(
            type: UpdateType::CALLBACK_QUERY,
            pattern: $pattern,
            handler: $handler,
            isPriority: $priority
        ));
    }

    /**
     * Registers a message text route by regex or exact match.
     * Set $priority to true to allow this route to execute even if an active Flow is running.
     */
    public function onMessage(?string $pattern, mixed $handler, bool $priority = false): Route
    {
        return $this->addRoute(new Route(
            type: UpdateType::MESSAGE,
            pattern: $pattern,
            handler: $handler,
            isPriority: $priority
        ));
    }

    /**
     * Convenience alias for onMessage.
     */
    public function onText(?string $pattern, mixed $handler, bool $priority = false): Route
    {
        return $this->onMessage($pattern, $handler, $priority);
    }

    /**
     * Registers an inline query route.
     * Set $priority to true to allow this route to execute even if an active Flow is running.
     */
    public function onInlineQuery(?string $pattern, mixed $handler, bool $priority = false): Route
    {
        return $this->addRoute(new Route(
            type: UpdateType::INLINE_QUERY,
            pattern: $pattern,
            handler: $handler,
            isPriority: $priority
        ));
    }

    /**
     * Registers a route for any specific UpdateType.
     * Set $priority to true to allow this route to execute even if an active Flow is running.
     */
    public function on(UpdateType|string $type, mixed $handler, bool $priority = false): Route
    {
        return $this->addRoute(new Route(
            type: $type,
            pattern: null,
            handler: $handler,
            isPriority: $priority
        ));
    }

    /**
     * Fallback handler called when no route matches the update.
     */
    public function onFallback(mixed $handler): static
    {
        $this->fallbackHandler = $handler;
        return $this;
    }

    /**
     * Registers an attribute-decorated Controller class or instance.
     * Automatically registers methods annotated with #[OnCommand], #[OnCallbackQuery], etc.
     */
    public function registerController(string|object $controller): static
    {
        $class = is_object($controller) ? $controller::class : $controller;
        if (!class_exists($class)) {
            throw new TelegramException("Controller class [{$class}] does not exist.");
        }

        $reflection = new ReflectionClass($class);

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $methodHandler = is_object($controller)
                ? [$controller, $method->getName()]
                : [$class, $method->getName()];

            // 1. #[OnCommand]
            foreach ($method->getAttributes(OnCommand::class) as $attr) {
                /** @var OnCommand $instance */
                $instance = $attr->newInstance();
                $this->onCommand($instance->command, $methodHandler, $instance->priority);
            }

            // 2. #[OnCallbackQuery]
            foreach ($method->getAttributes(OnCallbackQuery::class) as $attr) {
                /** @var OnCallbackQuery $instance */
                $instance = $attr->newInstance();
                $this->onCallbackQuery($instance->pattern, $methodHandler, $instance->priority);
            }

            // 3. #[OnMessage]
            foreach ($method->getAttributes(OnMessage::class) as $attr) {
                /** @var OnMessage $instance */
                $instance = $attr->newInstance();
                $this->onMessage($instance->pattern, $methodHandler, $instance->priority);
            }

            // 4. #[OnInlineQuery]
            foreach ($method->getAttributes(OnInlineQuery::class) as $attr) {
                /** @var OnInlineQuery $instance */
                $instance = $attr->newInstance();
                $this->onInlineQuery($instance->pattern, $methodHandler, $instance->priority);
            }

            // 5. #[OnUpdate]
            foreach ($method->getAttributes(OnUpdate::class) as $attr) {
                /** @var OnUpdate $instance */
                $instance = $attr->newInstance();
                $this->on($instance->type, $methodHandler, $instance->priority);
            }
        }

        return $this;
    }

    public function hasRoutes(): bool
    {
        return !empty($this->routes);
    }

    /**
     * Finds the first matching route. If $priorityOnly is true, only returns routes marked with priority.
     *
     * @param array<string, string> $parameters Output named parameters extracted from route pattern
     */
    public function findMatchingRoute(
        Update $update,
        Telegram $bot,
        bool $priorityOnly = false,
        array &$parameters = []
    ): ?Route {
        foreach ($this->routes as $route) {
            if ($priorityOnly && !$route->isPriority) {
                continue;
            }

            $params = [];
            if ($route->matches($update, $bot, $params)) {
                $parameters = $params;
                return $route;
            }
        }

        return null;
    }

    /**
     * Dispatches a single matching route directly, executing its route-level middlewares.
     */
    public function dispatchRoute(Route $route, Update $update, Telegram $bot, array $parameters = []): mixed
    {
        if (empty($route->middlewares)) {
            return $this->invokeHandler($route->handler, $update, $bot, $parameters);
        }

        $pipeline = array_reduce(
            array_reverse($route->middlewares),
            function (callable $next, callable $middleware) use ($parameters) {
                return function (Update $up, Telegram $b) use ($middleware, $next, $parameters) {
                    return $middleware($up, $b, fn(Update $u, Telegram $botInst) => $next($u, $botInst), $parameters);
                };
            },
            fn(Update $up, Telegram $b) => $this->invokeHandler($route->handler, $up, $b, $parameters)
        );

        return $pipeline($update, $bot);
    }

    /**
     * Dispatches the update through the registered routes.
     */
    public function dispatch(Update $update, Telegram $bot, bool $checkFlow = true): mixed
    {
        // 1. If user is in an active Flow, dispatch to Flow first!
        if ($checkFlow && $bot->flowManager->handle($update, $bot)) {
            return true;
        }

        foreach ($this->routes as $route) {
            $parameters = [];
            if ($route->matches($update, $bot, $parameters)) {
                return $this->dispatchRoute($route, $update, $bot, $parameters);
            }
        }

        if ($this->fallbackHandler !== null) {
            return $this->invokeHandler($this->fallbackHandler, $update, $bot, []);
        }

        return null;
    }

    /**
     * Makes Router directly invokable as a standard Tueen update handler.
     */
    public function __invoke(Update $update, Telegram $bot): mixed
    {
        return $this->dispatch($update, $bot);
    }

    public function invokeHandler(mixed $handler, Update $update, Telegram $bot, array $parameters): mixed
    {
        // If it's a [class/object, method] callable
        if (is_array($handler) && count($handler) === 2) {
            [$target, $method] = $handler;
            $instance = is_object($target) ? $target : $this->instantiateController($target, $bot);
            return $bot->dispatcher->invokeHandler([$instance, $method], $update, $bot, null, $parameters);
        }

        // If it's an invokable class string
        if (is_string($handler) && class_exists($handler)) {
            $instance = $this->instantiateController($handler, $bot);
            if (is_callable($instance)) {
                return $bot->dispatcher->invokeHandler($instance, $update, $bot, null, $parameters);
            }
            throw new TelegramException("Handler class [{$handler}] is not invokable.");
        }

        // If it's a closure / callable
        if (is_callable($handler)) {
            return $bot->dispatcher->invokeHandler($handler, $update, $bot, null, $parameters);
        }

        throw new TelegramException("Invalid route handler provided.");
    }

    private function instantiateController(string $class, Telegram $bot): object
    {
        // Container resolution if available
        $container = $bot->config->container;
        if ($container !== null) {
            if (is_object($container) && method_exists($container, 'has') && method_exists($container, 'get')) {
                if ($container->has($class)) {
                    return $container->get($class);
                }
            } elseif (is_callable($container)) {
                $resolved = $container($class);
                if (is_object($resolved)) {
                    return $resolved;
                }
            }
        }

        return new $class();
    }
}
