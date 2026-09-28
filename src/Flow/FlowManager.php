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
    private StateStoreInterface $store;

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

    public function getStore(): StateStoreInterface
    {
        return $this->store;
    }

    public function setStore(StateStoreInterface $store): static
    {
        $this->store = $store;
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

        $flowClass = $state->flowClass;
        if (!class_exists($flowClass) || !is_subclass_of($flowClass, Flow::class)) {
            $this->store->delete($sessionKey);
            return false;
        }

        $flow = $this->createFlowInstance($flowClass, $bot);
        $flow->init($bot, $update, $chatId, $userId, $state, $this);

        // Check for exit commands (e.g. /cancel)
        if ($flow->shouldExit($update)) {
            $flow->cancel();
            return true;
        }

        $step = $state->currentStep;
        if (!method_exists($flow, $step)) {
            // Step missing: abort flow safely
            $this->store->delete($sessionKey);
            return false;
        }

        $flow->$step($update);
        if (!$flow->isTerminated()) {
            $this->saveState($sessionKey, $flow->state, $flow->getTtl());
        }
        return true;
    }

    public function saveState(string $sessionKey, FlowState $state, ?int $ttl = null): void
    {
        $this->store->set($sessionKey, $state, $ttl);
    }

    public function deleteState(string $sessionKey): void
    {
        $this->store->delete($sessionKey);
    }

    private function createFlowInstance(string $flowClass, Telegram $bot): Flow
    {
        $container = $bot->getContainer();

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
}
