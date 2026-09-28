<?php

declare(strict_types=1);

namespace Tueen\Telegram\Flow\Storage;

use Tueen\Telegram\Flow\FlowState;

/**
 * Contract for storing and retrieving Flow states.
 */
interface StateStoreInterface
{
    /**
     * Retrieves the stored FlowState for the given session key.
     */
    public function get(string $key): ?FlowState;

    /**
     * Persists the FlowState for the given session key.
     *
     * @param int|null $ttl Time-to-live in seconds (null = default)
     */
    public function set(string $key, FlowState $state, ?int $ttl = null): void;

    /**
     * Deletes the FlowState for the given session key.
     */
    public function delete(string $key): void;

    /**
     * Clears all stored states.
     */
    public function clear(): void;
}
