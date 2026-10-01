<?php

declare(strict_types=1);

namespace Tueen\Telegram\Flow;

use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

/**
 * Provides an expressive, external controller and inspector for a chat's active conversation Flow.
 */
class FlowSession
{
    /**
     * Unique session key for this chat and user combination.
     */
    public string $sessionKey {
        get => FlowManager::resolveSessionKey($this->chatId, $this->userId);
    }

    /**
     * Checks if there is an active conversation Flow for this session.
     */
    public bool $isActive {
        get => $this->manager->hasActiveFlow($this->chatId, $this->userId);
    }

    /**
     * Retrieves the serialized FlowState for this session, or null if no flow is active.
     */
    public ?FlowState $state {
        get => $this->manager->getActiveState($this->chatId, $this->userId);
    }

    /**
     * Retrieves the active Flow class name (e.g. App\Flows\OrderFlow), or null if none active.
     *
     * @return class-string<Flow>|null
     */
    public ?string $class {
        get => $this->state?->flowClass;
    }

    /**
     * Retrieves the current step name in the active Flow, or null if none active.
     */
    public ?string $step {
        get => $this->state?->currentStep;
    }

    /**
     * Retrieves the step navigation history stack.
     *
     * @return list<string>
     */
    public array $history {
        get => $this->state?->history ?? [];
    }

    /**
     * Retrieves all stored state data array.
     *
     * @return array<string, mixed>
     */
    public array $data {
        get => $this->state?->data ?? [];
    }

    public function __construct(
        public readonly int|string $chatId,
        public readonly ?int $userId,
        public readonly Telegram $bot,
        public readonly FlowManager $manager,
        public readonly ?Update $update = null
    ) {}

    /**
     * Retrieves a specific value from the flow state data.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $state = $this->state;
        return $state !== null && array_key_exists($key, $state->data) ? $state->data[$key] : $default;
    }

    /**
     * Sets a key-value pair directly in the flow state data.
     */
    public function set(string $key, mixed $value): static
    {
        $state = $this->state;
        if ($state !== null) {
            $state->data[$key] = $value;
            $this->manager->saveState($this->sessionKey, $state);
        }
        return $this;
    }

    /**
     * Checks if a key exists in the flow state data.
     */
    public function has(string $key): bool
    {
        $state = $this->state;
        return $state !== null && array_key_exists($key, $state->data);
    }

    /**
     * Removes a key from the flow state data.
     */
    public function remove(string $key): static
    {
        $state = $this->state;
        if ($state !== null && array_key_exists($key, $state->data)) {
            unset($state->data[$key]);
            $this->manager->saveState($this->sessionKey, $state);
        }
        return $this;
    }

    /**
     * Instantiates and initializes the active Flow instance, or null if none active.
     */
    public function instance(): ?Flow
    {
        return $this->manager->getActiveFlowInstance($this->chatId, $this->userId, $this->bot, $this->update);
    }

    /**
     * Navigates back to the previous step (or pops to parent flow in an InteractiveFlow).
     *
     * @param string|null $replyMessage Optional message to send to the user
     * @return bool True if navigated back successfully, false if no active flow
     */
    public function back(?string $replyMessage = null): bool
    {
        return $this->manager->navigateBack($this->chatId, $this->userId, $this->bot, $replyMessage, $this->update);
    }

    /**
     * Advances the active flow to a specific step.
     *
     * @param string $step The method/step to execute next
     * @param array<string, mixed> $data Additional state data to merge
     * @return bool True if step was updated, false if no active flow
     */
    public function to(string $step, array $data = []): bool
    {
        $state = $this->state;
        if ($state === null) {
            return false;
        }

        if ($state->currentStep !== '') {
            $state->history[] = $state->currentStep;
        }
        $state->currentStep = $step;
        if (!empty($data)) {
            $state->data = array_merge($state->data, $data);
        }

        $this->manager->saveState($this->sessionKey, $state);
        return true;
    }

    /**
     * Seamlessly transitions the active flow to a new Flow class.
     *
     * @param class-string<Flow> $flowClass
     * @param string $initialStep
     * @param array<string, mixed> $initialData
     * @return bool
     */
    public function jumpTo(string $flowClass, string $initialStep = 'start', array $initialData = []): bool
    {
        $instance = $this->instance();
        if ($instance !== null) {
            $instance->jumpTo($flowClass, $initialStep, $initialData);
            return true;
        }

        $update = $this->update ?? $this->bot->update;
        if ($update !== null) {
            $this->manager->startFlow($flowClass, $update, $this->bot, $initialStep, $initialData);
            return true;
        }

        return false;
    }

    /**
     * Cancels the active flow and optionally notifies the user.
     *
     * @param string|null $replyMessage Message to send, or null for default cancellation notice
     * @return bool True if cancelled, false if no active flow
     */
    public function cancel(?string $replyMessage = 'Operation cancelled.'): bool
    {
        return $this->manager->cancelFlow($this->chatId, $this->userId, $this->bot, $replyMessage, $this->update);
    }

    /**
     * Finishes and clears the active flow state.
     *
     * @return bool True if finished, false if no active flow
     */
    public function finish(): bool
    {
        return $this->manager->finishFlow($this->chatId, $this->userId, $this->bot, $this->update);
    }

    /**
     * Resets the active flow to its initial 'start' step, clearing history and data.
     *
     * @return bool True if reset, false if no active flow
     */
    public function reset(): bool
    {
        $state = $this->state;
        if ($state === null) {
            return false;
        }

        $state->currentStep = 'start';
        $state->history = [];
        $state->data = [];
        $state->flowStack = [];

        $this->manager->saveState($this->sessionKey, $state);
        return true;
    }
}
