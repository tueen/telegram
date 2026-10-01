<?php

declare(strict_types=1);

namespace Tueen\Telegram\Routing;

use Tueen\Telegram\Enums\UpdateType;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

/**
 * Represents a registered route rule in the Tueen Router.
 */
class Route
{
    public readonly string $typeString;
    private bool $isCommand = false;
    private ?string $compiledRegex = null;

    /** @var list<callable> */
    private(set) array $middlewares = [];

    /** @var ?string */
    private(set) ?string $chatType = null;

    /** @var array<string, string> */
    private array $paramConditions = [];

    /** Parent Telegram instance for fluent chaining */
    public ?Telegram $bot = null;

    public function __construct(
        UpdateType|string $type,
        public readonly ?string $pattern,
        public readonly mixed $handler,
        bool $isCommand = false,
        public readonly bool $isPriority = false
    ) {
        $this->typeString = $type instanceof UpdateType ? $type->value : $type;
        $this->isCommand = $isCommand;
        $this->compilePattern();
    }

    private function compilePattern(): void
    {
        if ($this->pattern === null || $this->pattern === '' || $this->isCommand) {
            return;
        }

        // 1. Standard regex pattern (starts with delimiter)
        if (preg_match('/^([\/#~%]).*\1[imsxADSUXJu]*$/', $this->pattern)) {
            $this->compiledRegex = $this->pattern;
            return;
        }

        // 2. Placeholder matching (e.g. 'order:{id}', 'user/{id}', or 'action:{action}:{id}')
        if (str_contains($this->pattern, '{') && str_contains($this->pattern, '}')) {
            $tokenized = preg_replace_callback('/\{([a-zA-Z0-9_]+)\}/', function ($m) {
                return '___PARAM_' . $m[1] . '___';
            }, $this->pattern);

            $quoted = preg_quote($tokenized, '#');
            $this->compiledRegex = '#^' . preg_replace('/___PARAM_([a-zA-Z0-9_]+)___/', '(?P<$1>[^:/]+)', $quoted) . '$#';
            return;
        }

        // 3. Wildcard pattern (e.g. 'user:*' or '*help*')
        if (str_contains($this->pattern, '*')) {
            $quoted = preg_quote($this->pattern, '#');
            $this->compiledRegex = '#^' . str_replace('\*', '.*', $quoted) . '$#i';
            return;
        }
    }

    public function isCommand(): bool
    {
        return $this->isCommand;
    }

    public function isPriority(): bool
    {
        return $this->isPriority;
    }

    /**
     * Attaches route-level middlewares to this route.
     */
    public function middleware(callable ...$middlewares): static
    {
        foreach ($middlewares as $mw) {
            $this->middlewares[] = $mw;
        }
        return $this;
    }

    /**
     * Constrains this route to only match in private chats.
     */
    public function asPrivate(): static
    {
        $this->chatType = 'private';
        return $this;
    }

    /**
     * Constrains this route to only match in group chats.
     */
    public function asGroup(): static
    {
        $this->chatType = 'group';
        return $this;
    }

    /**
     * Constrains this route to only match in supergroup chats.
     */
    public function asSupergroup(): static
    {
        $this->chatType = 'supergroup';
        return $this;
    }

    /**
     * Constrains this route to only match in channels.
     */
    public function asChannel(): static
    {
        $this->chatType = 'channel';
        return $this;
    }

    /**
     * Constrains this route to match a specific chat type.
     */
    public function filterChatType(?string $type): static
    {
        $this->chatType = $type;
        return $this;
    }

    /**
     * Adds a regex validation condition to a route named parameter.
     */
    public function where(string $param, string $regex): static
    {
        $this->paramConditions[$param] = trim($regex, '#^$');
        return $this;
    }

    /**
     * Determines whether this route matches the given Update.
     *
     * @param array<string, string> $parameters Extracted named parameters from pattern
     */
    public function matches(Update $update, Telegram $bot, array &$parameters = []): bool
    {
        $parameters = [];
        $updateType = $update->type->value;

        // Check chat type scope if set
        if ($this->chatType !== null) {
            $chat = $update->findChat();
            $currentChatType = $chat?->type?->value ?? (string)$chat?->type;
            if ($currentChatType !== $this->chatType) {
                return false;
            }
        }

        // 1. Command matching
        if ($this->isCommand) {
            $msg = $update->findMessage();
            if ($msg === null || !$msg->isCommand()) {
                return false;
            }

            $command = $msg->command;
            $target = ltrim($this->pattern ?? '', '/');

            // Handle bot username mentions in command (e.g. /start@MyBot)
            if (str_contains($command ?? '', '@')) {
                $command = explode('@', $command)[0];
            }

            return strcasecmp($command ?? '', $target) === 0;
        }

        // 2. Update Type Check
        if ($this->typeString !== '*' && $this->typeString !== $updateType) {
            return false;
        }

        // If no specific pattern is required, matches this update type
        if ($this->pattern === null || $this->pattern === '') {
            return true;
        }

        // 3. Callback Query matching
        if ($updateType === UpdateType::CALLBACK_QUERY->value) {
            $data = $update->callbackQuery?->data ?? '';
            return $this->matchPattern($this->pattern, $data, $parameters);
        }

        // 4. Inline Query matching
        if ($updateType === UpdateType::INLINE_QUERY->value) {
            $query = $update->inlineQuery?->query ?? '';
            return $this->matchPattern($this->pattern, $query, $parameters);
        }

        // 5. Message text matching (including edited messages, channel posts, and business messages)
        $msg = $update->findMessage();
        if ($msg !== null) {
            $text = $msg->findAnyText() ?? '';
            return $this->matchPattern($this->pattern, $text, $parameters);
        }

        return false;
    }

    /**
     * Matches string against regex or placeholder syntax (e.g. 'user:{id}').
     */
    private function matchPattern(string $pattern, string $subject, array &$parameters): bool
    {
        // 1. Direct exact match (case-insensitive)
        if (strcasecmp($pattern, $subject) === 0) {
            return true;
        }

        // 2. Pre-compiled regex or placeholder match
        if ($this->compiledRegex !== null) {
            if (preg_match($this->compiledRegex, $subject, $matches)) {
                foreach ($matches as $k => $v) {
                    if (is_string($k)) {
                        if (isset($this->paramConditions[$k])) {
                            if (!preg_match('#^' . $this->paramConditions[$k] . '$#', $v)) {
                                return false;
                            }
                        }
                        $parameters[$k] = $v;
                    }
                }
                return true;
            }
            return false;
        }

        return false;
    }

    /**
     * Proxies method calls to the parent Telegram bot instance for fluent chaining.
     */
    public function __call(string $name, array $arguments): mixed
    {
        if ($this->bot !== null && method_exists($this->bot, $name)) {
            return $this->bot->$name(...$arguments);
        }

        throw new \BadMethodCallException("Method {$name} does not exist on " . static::class);
    }
}
