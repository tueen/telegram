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

    private mixed $fallbackHandler = null;

    /**
     * Registers a bot command route (e.g. 'start', '/help').
     */
    public function onCommand(string $command, mixed $handler): static
    {
        $this->routes[] = new Route(
            type: UpdateType::MESSAGE,
            pattern: ltrim($command, '/'),
            handler: $handler,
            isCommand: true
        );
        return $this;
    }

    /**
     * Registers a callback query route by exact match, placeholder ('item:{id}'), or regex.
     */
    public function onCallbackQuery(?string $pattern, mixed $handler): static
    {
        $this->routes[] = new Route(
            type: UpdateType::CALLBACK_QUERY,
            pattern: $pattern,
            handler: $handler
        );
        return $this;
    }

    /**
     * Registers a message text route by regex or substring.
     */
    public function onMessage(?string $pattern, mixed $handler): static
    {
        $this->routes[] = new Route(
            type: UpdateType::MESSAGE,
            pattern: $pattern,
            handler: $handler
        );
        return $this;
    }

    /**
     * Registers an inline query route.
     */
    public function onInlineQuery(?string $pattern, mixed $handler): static
    {
        $this->routes[] = new Route(
            type: UpdateType::INLINE_QUERY,
            pattern: $pattern,
            handler: $handler
        );
        return $this;
    }

    /**
     * Registers a route for any specific UpdateType.
     */
    public function on(UpdateType|string $type, mixed $handler): static
    {
        $this->routes[] = new Route(
            type: $type,
            pattern: null,
            handler: $handler
        );
        return $this;
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
                $this->onCommand($instance->command, $methodHandler);
            }

            // 2. #[OnCallbackQuery]
            foreach ($method->getAttributes(OnCallbackQuery::class) as $attr) {
                /** @var OnCallbackQuery $instance */
                $instance = $attr->newInstance();
                $this->onCallbackQuery($instance->pattern, $methodHandler);
            }

            // 3. #[OnMessage]
            foreach ($method->getAttributes(OnMessage::class) as $attr) {
                /** @var OnMessage $instance */
                $instance = $attr->newInstance();
                $this->onMessage($instance->pattern, $methodHandler);
            }

            // 4. #[OnInlineQuery]
            foreach ($method->getAttributes(OnInlineQuery::class) as $attr) {
                /** @var OnInlineQuery $instance */
                $instance = $attr->newInstance();
                $this->onInlineQuery($instance->pattern, $methodHandler);
            }

            // 5. #[OnUpdate]
            foreach ($method->getAttributes(OnUpdate::class) as $attr) {
                /** @var OnUpdate $instance */
                $instance = $attr->newInstance();
                $this->on($instance->type, $methodHandler);
            }
        }

        return $this;
    }

    public function hasRoutes(): bool
    {
        return !empty($this->routes);
    }

    /**
     * Dispatches the update through the registered routes.
     */
    public function dispatch(Update $update, Telegram $bot): mixed
    {
        foreach ($this->routes as $route) {
            $parameters = [];
            if ($route->matches($update, $bot, $parameters)) {
                return $this->invokeHandler($route->getHandler(), $update, $bot, $parameters);
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

    private function invokeHandler(mixed $handler, Update $update, Telegram $bot, array $parameters): mixed
    {
        // If it's a [class/object, method] callable
        if (is_array($handler) && count($handler) === 2) {
            [$target, $method] = $handler;
            $instance = is_object($target) ? $target : $this->instantiateController($target, $bot);
            return $instance->{$method}($update, $bot, ...$parameters);
        }

        // If it's an invokable class string
        if (is_string($handler) && class_exists($handler)) {
            $instance = $this->instantiateController($handler, $bot);
            if (is_callable($instance)) {
                return $instance($update, $bot, ...$parameters);
            }
            throw new TelegramException("Handler class [{$handler}] is not invokable.");
        }

        // If it's a closure / callable
        if (is_callable($handler)) {
            return $handler($update, $bot, ...$parameters);
        }

        throw new TelegramException("Invalid route handler provided.");
    }

    private function instantiateController(string $class, Telegram $bot): object
    {
        // Container resolution if available
        $container = $bot->getConfig()->container;
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
