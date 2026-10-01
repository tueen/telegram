<?php

declare(strict_types=1);

namespace Tueen\Telegram\Flow\Storage;

use Tueen\Telegram\Flow\FlowState;

/**
 * In-memory state store, ideal for testing, development, and single-process polling.
 */
class MemoryStateStore implements StateStoreInterface
{
    /** @var array<string, FlowState> */
    protected array $states = [];

    #[\Override]
    public function get(string $key): ?FlowState
    {
        if (!isset($this->states[$key])) {
            return null;
        }

        $state = $this->states[$key];
        if ($state->isExpired) {
            unset($this->states[$key]);
            return null;
        }

        return $state;
    }

    #[\Override]
    public function set(string $key, FlowState $state, ?int $ttl = null): void
    {
        if ($ttl !== null && $ttl > 0) {
            $state->expiresAt = time() + $ttl;
        }
        $state->updatedAt = time();
        $this->states[$key] = $state;
    }

    #[\Override]
    public function delete(string $key): void
    {
        unset($this->states[$key]);
    }

    #[\Override]
    public function clear(): void
    {
        $this->states = [];
    }

    /**
     * Returns all active states.
     * @return array<string, FlowState>
     */
    public function all(): array
    {
        return $this->states;
    }
}
