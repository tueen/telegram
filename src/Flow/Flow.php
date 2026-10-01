<?php

declare(strict_types=1);

namespace Tueen\Telegram\Flow;

use Tueen\Telegram\Keyboards\InlineKeyboard;
use Tueen\Telegram\Keyboards\ReplyKeyboard;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

/**
 * Base class for all multi-step conversation flows in Tueen.
 *
 * Each step in a Flow is simply a public method on this class.
 *
 * Example:
 * class RegistrationFlow extends Flow
 * {
 *     public function start(Update $update): void
 *     {
 *         $this->bot->sendMessage(chatId: $this->chatId, text: 'What is your name?');
 *         $this->to('askEmail');
 *     }
 *
 *     public function askEmail(Update $update): void
 *     {
 *         $name = $update->message?->text;
 *         $this->set('name', $name);
 *         $this->bot->sendMessage(chatId: $this->chatId, text: "Nice to meet you, {$name}! What is your email?");
 *         $this->to('confirm');
 *     }
 *
 *     public function confirm(Update $update): void
 *     {
 *         $email = $update->message?->text;
 *         $this->bot->sendMessage(chatId: $this->chatId, text: "Done! Saved email: {$email}");
 *         $this->finish();
 *     }
 * }
 */
abstract class Flow
{
    protected(set) Telegram $bot;
    protected(set) Update $update;
    protected(set) int|string $chatId;
    protected(set) ?int $userId = null;
    protected(set) FlowState $state;
    protected(set) FlowManager $manager;

    /**
     * Unique session key for this chat and user combination.
     */
    public string $sessionKey {
        get => FlowManager::resolveSessionKey($this->chatId, $this->userId);
    }

    /**
     * Commands that immediately cancel and exit the flow (e.g. ['/cancel', '/exit']).
     * @var list<string>
     */
    protected array $exitCommands = ['/cancel', '/exit', '/stop'];

    /**
     * Allowed update types for this flow.
     * An empty array indicates all update types are accepted (or uses default from config).
     *
     * @var list<string|\Tueen\Telegram\Enums\UpdateType>
     */
    protected array $allowedUpdates = [];

    /**
     * Optional fallback Flow class to transition to if an unresolvable error or missing step occurs.
     *
     * @var class-string<Flow>|null
     */
    protected ?string $fallbackFlow = null;

    /**
     * Time-to-live for this flow state in seconds (default: 3600 = 1 hour).
     * Set to null for unlimited lifetime.
     */
    protected(set) ?int $ttl = 3600;

    /**
     * Internal flag indicating whether the flow has finished or moved.
     */
    protected(set) bool $isTerminated = false;

    /**
     * Initializes the Flow instance with runtime context.
     */
    final public function init(
        Telegram $bot,
        Update $update,
        int|string $chatId,
        ?int $userId,
        FlowState $state,
        FlowManager $manager
    ): static {
        $this->bot = $bot;
        $this->update = $update;
        $this->chatId = $chatId;
        $this->userId = $userId;
        $this->state = $state;
        $this->manager = $manager;
        return $this;
    }

    /**
     * Default entry point step if no other step is specified.
     */
    public function start(Update $update): void
    {
        // Child classes can implement or override this
    }

    /**
     * Lifecycle hook invoked when the flow terminates (finished, cancelled, or interrupted).
     *
     * @param Update $update The update triggering the exit
     * @param string $reason One of 'finished', 'cancelled', 'interrupted'
     */
    public function onExit(Update $update, string $reason): void
    {
        // Child classes can override this for cleanup or analytics
    }

    /**
     * Returns the list of allowed update types for this flow.
     *
     * @return list<string|\Tueen\Telegram\Enums\UpdateType>
     */
    public function allowedUpdates(): array
    {
        if (!empty($this->allowedUpdates)) {
            return $this->allowedUpdates;
        }

        // Check for #[AllowedUpdates] attribute
        $ref = new \ReflectionClass($this);
        $attrs = $ref->getAttributes(\Tueen\Telegram\Flow\Attributes\AllowedUpdates::class);
        if (!empty($attrs)) {
            /** @var \Tueen\Telegram\Flow\Attributes\AllowedUpdates $instance */
            $instance = $attrs[0]->newInstance();
            return $instance->types;
        }

        return $this->manager->defaultAllowedUpdates;
    }

    /**
     * Determines whether an incoming update is allowed to be handled by this flow.
     */
    public function allowsUpdate(Update $update): bool
    {
        $allowed = $this->allowedUpdates();
        if (empty($allowed)) {
            return true;
        }

        $type = $update->type;
        foreach ($allowed as $item) {
            if ($item instanceof \Tueen\Telegram\Enums\UpdateType && $item === $type) {
                return true;
            }
            if (is_string($item)) {
                if ($item === '*' || strtolower($item) === strtolower($type->value)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Returns the fallback Flow class configured for this flow.
     *
     * @return class-string<Flow>|null
     */
    public function fallbackFlow(): ?string
    {
        return $this->fallbackFlow;
    }

    /**
     * Handles an incoming update when the target step method does not exist on this Flow.
     * By default, attempts to navigate back in history, or fallback to start() / fallbackFlow / rootFlow.
     */
    public function onMissingStep(string $step, Update $update): void
    {
        // 1. If history exists, navigate back to previous step
        if (!empty($this->state->history)) {
            $this->back();
            return;
        }

        // 2. If InteractiveFlow and parent stack exists, pop to parent flow
        if ($this instanceof InteractiveFlow && !empty($this->state->flowStack)) {
            $this->pop();
            return;
        }

        // 3. Fallback to start() method if current step is not already start
        if ($step !== 'start' && method_exists($this, 'start')) {
            $this->state->currentStep = 'start';
            $this->manager->saveState($this->sessionKey, $this->state, $this->ttl);
            $this->start($update);
            return;
        }

        // 4. Fallback to custom fallbackFlow if specified
        $fallback = $this->fallbackFlow();
        if ($fallback !== null && $fallback !== static::class && class_exists($fallback)) {
            $this->jumpTo($fallback);
            return;
        }

        // 5. Fallback to rootFlow if configured on FlowManager
        $rootFlow = $this->manager->rootFlow;
        if ($rootFlow !== null && $rootFlow !== static::class && class_exists($rootFlow)) {
            $this->jumpTo($rootFlow);
            return;
        }

        // 6. Otherwise safely finish flow
        $this->finish();
    }

    /**
     * Checks if the incoming update matches one of the exit commands.
     */
    public function shouldExit(Update $update): bool
    {
        $text = trim($update->findAnyText() ?? '');
        if ($text === '') {
            return false;
        }

        // Strip bot username, e.g. /cancel@my_bot -> /cancel
        $parts = explode('@', $text, 2);
        $command = strtolower($parts[0]);

        return in_array($command, array_map('strtolower', $this->exitCommands), true);
    }

    /**
     * Advances the flow to the specified next step.
     *
     * @param string $stepMethod The name of the method to invoke on the next update
     * @param array<string, mixed> $data Optional additional data to merge into flow state
     */
    public function to(string $stepMethod, array $data = []): static
    {
        if ($this->state->currentStep !== '') {
            $this->state->history[] = $this->state->currentStep;
        }

        $this->state->currentStep = $stepMethod;
        if (!empty($data)) {
            $this->state->data = array_merge($this->state->data, $data);
        }

        $this->manager->saveState($this->sessionKey, $this->state, $this->ttl);
        return $this;
    }

    /**
     * Keeps the user on the current step, optionally sending a reply message.
     *
     * @param string|null $replyMessage Optional message to send back to the user
     * @param InlineKeyboard|ReplyKeyboard|null $keyboard Optional keyboard markup
     */
    public function stay(?string $replyMessage = null, mixed $keyboard = null): static
    {
        if ($replyMessage !== null && $replyMessage !== '') {
            $params = [
                'chatId' => $this->chatId,
                'text' => $replyMessage,
            ];
            if ($keyboard !== null) {
                $params['replyMarkup'] = $keyboard;
            }
            $this->bot->sendMessage(...$params);
        }

        $this->manager->saveState($this->sessionKey, $this->state, $this->ttl);
        return $this;
    }

    /**
     * Navigates back to the previous step in history.
     *
     * @param string|null $replyMessage Optional message to send when going back
     */
    public function back(?string $replyMessage = null): static
    {
        if (!empty($this->state->history)) {
            $previousStep = array_pop($this->state->history);
            $this->state->currentStep = $previousStep;
            $this->manager->saveState($this->sessionKey, $this->state, $this->ttl);

            if ($replyMessage !== null && $replyMessage !== '') {
                $this->bot->sendMessage(chatId: $this->chatId, text: $replyMessage);
            }

            return $this;
        }

        // If no previous step exists, finish the flow
        $this->finish();
        return $this;
    }

    /**
     * Successfully completes the flow and removes its persisted state.
     */
    public function finish(): void
    {
        $this->isTerminated = true;
        $this->manager->deleteState($this->sessionKey);
        $this->onExit($this->update, 'finished');
    }

    /**
     * Cancels the flow, removes persisted state, and optionally informs the user.
     */
    public function cancel(?string $replyMessage = 'Operation cancelled.'): void
    {
        $this->isTerminated = true;
        $this->manager->deleteState($this->sessionKey);
        $this->onExit($this->update, 'cancelled');

        if ($replyMessage !== null && $replyMessage !== '') {
            $this->bot->sendMessage(chatId: $this->chatId, text: $replyMessage);
        }
    }

    /**
     * Seamlessly transitions the user to another Flow class.
     *
     * @param class-string<Flow> $flowClass
     * @param string $initialStep
     * @param array<string, mixed> $initialData
     * @param array<string, mixed> $data
     */
    public function jumpTo(
        string $flowClass,
        string $initialStep = 'start',
        array $initialData = [],
        array $data = []
    ): void {
        $this->isTerminated = true;
        $this->onExit($this->update, 'interrupted');
        $passedData = !empty($initialData) ? $initialData : $data;
        $mergedData = array_merge($this->state->data, $passedData);
        $this->manager->startFlow($flowClass, $this->update, $this->bot, $initialStep, $mergedData);
    }

    /**
     * Persists a key-value pair into the Flow's state.
     */
    public function set(string $key, mixed $value): static
    {
        $this->state->data[$key] = $value;
        $this->manager->saveState($this->sessionKey, $this->state, $this->ttl);
        return $this;
    }

    /**
     * Retrieves a value from the Flow's state.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->state->data[$key] ?? $default;
    }

    /**
     * Checks if a key exists in the Flow's state.
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->state->data);
    }

    /**
     * Removes a key from the Flow's state.
     */
    public function remove(string $key): static
    {
        unset($this->state->data[$key]);
        $this->manager->saveState($this->sessionKey, $this->state, $this->ttl);
        return $this;
    }

    /**
     * Returns all stored state data.
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->state->data;
    }

    /**
     * Clears all stored data in this flow without exiting the step.
     */
    public function clearData(): static
    {
        $this->state->data = [];
        $this->manager->saveState($this->sessionKey, $this->state, $this->ttl);
        return $this;
    }

    public function __get(string $name): mixed
    {
        return $this->get($name);
    }

    public function __set(string $name, mixed $value): void
    {
        $this->set($name, $value);
    }

    public function __isset(string $name): bool
    {
        return $this->has($name);
    }

    public function __unset(string $name): void
    {
        $this->remove($name);
    }
}
