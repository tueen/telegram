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
    private string $typeString;
    private bool $isCommand = false;

    public function __construct(
        UpdateType|string $type,
        private readonly ?string $pattern,
        private readonly mixed $handler,
        bool $isCommand = false
    ) {
        $this->typeString = $type instanceof UpdateType ? $type->value : $type;
        $this->isCommand = $isCommand;
    }

    public function getHandler(): mixed
    {
        return $this->handler;
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

        // 1. Command matching
        if ($this->isCommand) {
            $msg = $update->findMessage();
            if ($msg === null || !$msg->isCommand()) {
                return false;
            }

            $command = $msg->getCommand();
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
        // Direct exact match
        if ($pattern === $subject) {
            return true;
        }

        // Standard regex pattern (starts with delimiter)
        if (preg_match('/^([\/#~%]).*\1[imsxADSUXJu]*$/', $pattern)) {
            if (preg_match($pattern, $subject, $matches)) {
                foreach ($matches as $k => $v) {
                    if (is_string($k)) {
                        $parameters[$k] = $v;
                    }
                }
                return true;
            }
            return false;
        }

        // Placeholder matching (e.g. 'order:{id}', 'user/{id}', or 'action:{action}:{id}')
        if (str_contains($pattern, '{') && str_contains($pattern, '}')) {
            $tokenized = preg_replace_callback('/\{([a-zA-Z0-9_]+)\}/', function ($m) {
                return '___PARAM_' . $m[1] . '___';
            }, $pattern);

            $quoted = preg_quote($tokenized, '#');

            $regex = preg_replace('/___PARAM_([a-zA-Z0-9_]+)___/', '(?P<$1>[^:/]+)', $quoted);

            if (preg_match('#^' . $regex . '$#', $subject, $matches)) {
                foreach ($matches as $k => $v) {
                    if (is_string($k)) {
                        $parameters[$k] = $v;
                    }
                }
                return true;
            }
        }

        // Substring / case-insensitive match for plain text
        if (stripos($subject, $pattern) !== false) {
            return true;
        }

        return false;
    }
}
