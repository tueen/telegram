<?php

declare(strict_types=1);

namespace Tueen\Telegram\Flow;

use Tueen\Telegram\Exceptions\TelegramException;
use Tueen\Telegram\Flow\Storage\FileStateStore;
use Tueen\Telegram\Flow\Storage\MemoryStateStore;
use Tueen\Telegram\Flow\Storage\StateStoreInterface;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

/**
 * Manages active Flow sessions and orchestrates step dispatching.
 */
class FlowManager
{
    public StateStoreInterface $store;
    public ?string $rootFlow = null;
    /** @var list<string|\Tueen\Telegram\Enums\UpdateType> */
    public array $defaultAllowedUpdates = [];

    public function __construct(?StateStoreInterface $store = null)
    {
        if ($store !== null) {
            $this->store = $store;
        } elseif (PHP_SAPI !== 'cli') {
            // In web server SAPIs (FPM, Apache, CGI), memory is ephemeral and wiped on script termination.
            // Automatically use FileStateStore for zero-config persistence across webhook requests.
            $this->store = new FileStateStore();
        } else {
            $this->store = new MemoryStateStore();
        }
    }

    public function setStore(StateStoreInterface $store): static
    {
        $this->store = $store;
        return $this;
    }

    /**
     * Sets the default root/home flow to redirect to upon home navigation or /start.
     *
     * @param class-string<Flow>|null $flowClass
     */
    public function setRootFlow(?string $flowClass): static
    {
        $this->rootFlow = $flowClass;
        return $this;
    }

    /**
     * Sets the default allowed update types for flows that do not specify their own.
     *
     * @param list<string|\Tueen\Telegram\Enums\UpdateType> $types
     */
    public function setDefaultAllowedUpdates(array $types): static
    {
        $this->defaultAllowedUpdates = $types;
        return $this;
    }

    /**
     * Resolves a unique session key for a chat/user pair.
     */
    public static function resolveSessionKey(int|string $chatId, ?int $userId = null): string
    {
        if ($userId !== null && (string) $chatId !== (string) $userId) {
            return "chat:{$chatId}:user:{$userId}";
        }

        return "chat:{$chatId}";
    }

    /**
     * Checks if there is an active flow for the given chat/user.
     */
    public function hasActiveFlow(int|string $chatId, ?int $userId = null): bool
    {
        $key = self::resolveSessionKey($chatId, $userId);
        return $this->store->get($key) !== null;
    }

    /**
     * Retrieves the active FlowState for the given chat/user, or null if none active.
     */
    public function getActiveState(int|string $chatId, ?int $userId = null): ?FlowState
    {
        $key = self::resolveSessionKey($chatId, $userId);
        return $this->store->get($key);
    }

    /**
     * Resolves the active Flow instance for the given update, or null if no flow is currently active.
     */
    public function getActiveFlow(Update $update, Telegram $bot): ?Flow
    {
        $chat = $update->findChat();
        if ($chat === null) {
            return null;
        }

        $user = $update->findUser();
        $chatId = $chat->id;
        $userId = $user?->id;
        $sessionKey = self::resolveSessionKey($chatId, $userId);

        $state = $this->store->get($sessionKey);
        if ($state === null || $state->isExpired()) {
            return null;
        }

        $flowClass = $state->flowClass;
        if (!class_exists($flowClass) || !is_subclass_of($flowClass, Flow::class)) {
            return null;
        }

        $flow = $this->createFlowInstance($flowClass, $bot);
        $flow->init($bot, $update, $chatId, $userId, $state, $this);

        return $flow;
    }

    /**
     * Initiates a new Flow for the user associated with the update.
     *
     * @param class-string<Flow> $flowClass
     * @param Update $update
     * @param Telegram $bot
     * @param string $initialStep
     * @param array<string, mixed> $initialData
     * @return Flow
     */
    public function startFlow(
        string $flowClass,
        Update $update,
        Telegram $bot,
        string $initialStep = 'start',
        array $initialData = []
    ): Flow {
        $chat = $update->findChat();
        if ($chat === null) {
            throw new TelegramException("Cannot start Flow: unable to resolve chat from Update.");
        }

        $user = $update->findUser();
        $chatId = $chat->id;
        $userId = $user?->id;
        $sessionKey = self::resolveSessionKey($chatId, $userId);

        $state = new FlowState(
            flowClass: $flowClass,
            currentStep: $initialStep,
            data: $initialData
        );

        $flow = $this->createFlowInstance($flowClass, $bot);
        $flow->init($bot, $update, $chatId, $userId, $state, $this);

        // Persist initial state
        $this->saveState($sessionKey, $state);

        // Execute initial step
        if (method_exists($flow, $initialStep)) {
            $flow->$initialStep($update);
        }

        return $flow;
    }

    /**
     * Handles an incoming Update. If the user is inside an active Flow, dispatches
     * the update to the flow's current step and returns true. Otherwise returns false.
     */
    public function handle(Update $update, Telegram $bot): bool
    {
        $chat = $update->findChat();
        if ($chat === null) {
            return false;
        }

        $user = $update->findUser();
        $chatId = $chat->id;
        $userId = $user?->id;
        $sessionKey = self::resolveSessionKey($chatId, $userId);

        $state = $this->store->get($sessionKey);
        if ($state === null) {
            return false;
        }

        if ($state->isExpired()) {
            $this->store->delete($sessionKey);
            return false;
        }

        $flowClass = $state->flowClass;
        if (!class_exists($flowClass) || !is_subclass_of($flowClass, Flow::class)) {
            return $this->handleMissingFlowClass($sessionKey, $state, $update, $bot, $chatId, $userId);
        }

        $flow = $this->createFlowInstance($flowClass, $bot);
        $flow->init($bot, $update, $chatId, $userId, $state, $this);

        // Check if update type is allowed for this Flow
        if (!$flow->allowsUpdate($update)) {
            return false;
        }

        // Check for /start command
        $text = trim($update->findAnyText() ?? '');
        $isStartCommand = str_starts_with(strtolower($text), '/start');

        if ($isStartCommand) {
            if ($flow instanceof InteractiveFlow) {
                if (!$flow->canInterrupt($update)) {
                    $flow->handleUpdate($update);
                    if (!$flow->isTerminated) {
                        $this->saveState($sessionKey, $flow->state, $flow->ttl);
                    }
                    return true;
                }

                if ($this->rootFlow !== null && $this->rootFlow !== $flowClass) {
                    $flow->home();
                    return true;
                }
            } elseif ($this->rootFlow !== null && $this->rootFlow !== $flowClass) {
                $flow->cancel();
                $this->startFlow($this->rootFlow, $update, $bot);
                return true;
            }
        }

        // Check for exit commands (e.g. /cancel)
        if ($flow->shouldExit($update)) {
            $flow->cancel();
            return true;
        }

        if ($flow instanceof InteractiveFlow) {
            $handled = $flow->handleUpdate($update);
            if ($flow->isPassedThrough || !$handled) {
                if (!$flow->isTerminated) {
                    $this->saveState($sessionKey, $flow->state, $flow->ttl);
                }
                return false;
            }
            if (!$flow->isTerminated) {
                $this->saveState($sessionKey, $flow->state, $flow->ttl);
            }
            return true;
        }

        $step = $state->currentStep;
        if (!method_exists($flow, $step)) {
            $flow->onMissingStep($step, $update);
            if ($flow->isPassedThrough) {
                if (!$flow->isTerminated) {
                    $this->saveState($sessionKey, $flow->state, $flow->ttl);
                }
                return false;
            }
            if (!$flow->isTerminated) {
                $this->saveState($sessionKey, $flow->state, $flow->ttl);
            }
            return true;
        }

        $result = $flow->$step($update);
        if ($result === false || $flow->isPassedThrough) {
            if (!$flow->isTerminated) {
                $this->saveState($sessionKey, $flow->state, $flow->ttl);
            }
            return false;
        }

        if (!$flow->isTerminated) {
            $this->saveState($sessionKey, $flow->state, $flow->ttl);
        }
        return true;
    }

    /**
     * Resiliently handles incoming updates when the active Flow class was deleted or cannot be resolved.
     * Navigates back in the flow stack, falls back to the configured rootFlow, or safely terminates.
     */
    protected function handleMissingFlowClass(
        string $sessionKey,
        FlowState $state,
        Update $update,
        Telegram $bot,
        int|string $chatId,
        ?int $userId
    ): bool {
        // 1. Attempt to navigate back in hierarchical flow stack (pop parent flows)
        while (!empty($state->flowStack)) {
            $parentSnapshot = array_pop($state->flowStack);
            $parentClass = $parentSnapshot['flowClass'] ?? '';

            if (class_exists($parentClass) && is_subclass_of($parentClass, Flow::class)) {
                $state->flowClass = $parentClass;
                $state->currentStep = $parentSnapshot['currentStep'] ?? 'start';
                $state->data = $parentSnapshot['data'] ?? [];
                if (isset($parentSnapshot['messageId'])) {
                    $state->messageId = $parentSnapshot['messageId'];
                }

                $parentFlow = $this->createFlowInstance($parentClass, $bot);
                $parentFlow->init($bot, $update, $chatId, $userId, $state, $this);

                if ($parentFlow instanceof InteractiveFlow) {
                    $parentFlow->onResume(null);
                    $parentFlow->renderScreen();
                } else {
                    $step = $parentFlow->state->currentStep;
                    if (method_exists($parentFlow, $step)) {
                        $parentFlow->$step($update);
                    } elseif (method_exists($parentFlow, 'start')) {
                        $parentFlow->start($update);
                    }
                }

                if (!$parentFlow->isTerminated) {
                    $this->saveState($sessionKey, $parentFlow->state, $parentFlow->ttl);
                }

                return true;
            }
        }

        // 2. Fallback to configured root flow
        $rootFlow = $this->rootFlow;
        if ($rootFlow !== null && class_exists($rootFlow) && is_subclass_of($rootFlow, Flow::class)) {
            $this->store->delete($sessionKey);
            $this->startFlow($rootFlow, $update, $bot);
            return true;
        }

        // 3. Fallback: safely delete state and let regular bot routes process the update
        $this->store->delete($sessionKey);
        return false;
    }

    public function saveState(string $sessionKey, FlowState $state, ?int $ttl = null): void
    {
        $this->store->set($sessionKey, $state, $ttl);
    }

    public function deleteState(string $sessionKey): void
    {
        $this->store->delete($sessionKey);
    }

    public function createFlowInstance(string $flowClass, Telegram $bot): Flow
    {
        $container = $bot->container;

        if ($container !== null) {
            if (is_object($container) && method_exists($container, 'get') && method_exists($container, 'has')) {
                if ($container->has($flowClass)) {
                    return $container->get($flowClass);
                }
            } elseif (is_callable($container)) {
                $instance = $container($flowClass);
                if ($instance instanceof Flow) {
                    return $instance;
                }
            }
        }

        return new $flowClass();
    }

    /**
     * Resolves a fluent FlowSession object to inspect and control a flow from outside.
     */
    public function flowSession(int|string $chatId, ?int $userId, Telegram $bot, ?Update $update = null): FlowSession
    {
        return new FlowSession($chatId, $userId, $bot, $this, $update);
    }

    /**
     * Gets the active Flow class for the specified session, or null if none active.
     *
     * @return class-string<Flow>|null
     */
    public function getActiveFlowClass(int|string $chatId, ?int $userId = null): ?string
    {
        return $this->getActiveState($chatId, $userId)?->flowClass;
    }

    /**
     * Instantiates and initializes the active Flow instance, or null if none active.
     */
    public function getActiveFlowInstance(
        int|string $chatId,
        ?int $userId,
        Telegram $bot,
        ?Update $update = null
    ): ?Flow {
        $state = $this->getActiveState($chatId, $userId);
        if ($state === null) {
            return null;
        }

        $flowClass = $state->flowClass;
        if (!class_exists($flowClass) || !is_subclass_of($flowClass, Flow::class)) {
            return null;
        }

        $resolvedUpdate = $update ?? $bot->update ?? new Update([]);
        $flow = $this->createFlowInstance($flowClass, $bot);
        $flow->init($bot, $resolvedUpdate, $chatId, $userId, $state, $this);
        return $flow;
    }

    /**
     * Cancels the active flow for the given session.
     */
    public function cancelFlow(
        int|string $chatId,
        ?int $userId,
        Telegram $bot,
        ?string $replyMessage = 'Operation cancelled.',
        ?Update $update = null
    ): bool {
        $flow = $this->getActiveFlowInstance($chatId, $userId, $bot, $update);
        if ($flow === null) {
            $key = self::resolveSessionKey($chatId, $userId);
            if ($this->store->get($key) !== null) {
                $this->store->delete($key);
                return true;
            }
            return false;
        }

        $flow->cancel($replyMessage);
        return true;
    }

    /**
     * Finishes the active flow for the given session.
     */
    public function finishFlow(
        int|string $chatId,
        ?int $userId = null,
        ?Telegram $bot = null,
        ?Update $update = null
    ): bool {
        if ($bot !== null) {
            $flow = $this->getActiveFlowInstance($chatId, $userId, $bot, $update);
            if ($flow !== null) {
                $flow->finish();
                return true;
            }
        }

        $key = self::resolveSessionKey($chatId, $userId);
        if ($this->store->get($key) !== null) {
            $this->store->delete($key);
            return true;
        }

        return false;
    }

    /**
     * Navigates back in history or pops parent flow for the active session.
     */
    public function navigateBack(
        int|string $chatId,
        ?int $userId,
        Telegram $bot,
        ?string $replyMessage = null,
        ?Update $update = null
    ): bool {
        $flow = $this->getActiveFlowInstance($chatId, $userId, $bot, $update);
        if ($flow === null) {
            return false;
        }

        if ($flow instanceof InteractiveFlow) {
            $flow->pop();
        } else {
            $flow->back($replyMessage);
        }

        return true;
    }
}
