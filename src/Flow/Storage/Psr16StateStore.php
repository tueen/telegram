<?php

declare(strict_types=1);

namespace Tueen\Telegram\Flow\Storage;

use Tueen\Telegram\Flow\FlowState;

/**
 * Standard PSR-16 (SimpleCache) state storage driver for Tueen Flow sessions.
 *
 * Compatible with Laravel Cache, Symfony Cache, and all PSR-16 drivers.
 */
class Psr16StateStore implements StateStoreInterface
{
    /**
     * @param object $cache PSR-16 compliant cache instance (implements get, set, delete, clear)
     * @param string $prefix Cache key prefix
     */
    public function __construct(
        private readonly object $cache,
        private readonly string $prefix = 'tueen:flow:'
    ) {}

    #[\Override]
    public function get(string $key): ?FlowState
    {
        $prefixedKey = $this->prefix . $key;
        $cached = $this->cache->get($prefixedKey);

        if ($cached === null || $cached === false) {
            return null;
        }

        if (is_string($cached)) {
            $data = json_decode($cached, true);
            if (!is_array($data)) {
                return null;
            }
            $state = FlowState::fromArray($data);
        } elseif (is_array($cached)) {
            $state = FlowState::fromArray($cached);
        } elseif ($cached instanceof FlowState) {
            $state = $cached;
        } else {
            return null;
        }

        if ($state->isExpired) {
            $this->delete($key);
            return null;
        }

        return $state;
    }

    #[\Override]
    public function set(string $key, FlowState $state, ?int $ttl = null): void
    {
        $prefixedKey = $this->prefix . $key;

        if ($ttl !== null && $ttl > 0) {
            $state->expiresAt = time() + $ttl;
        }
        $state->updatedAt = time();

        $this->cache->set($prefixedKey, $state->toArray(), $ttl);
    }

    #[\Override]
    public function delete(string $key): void
    {
        $prefixedKey = $this->prefix . $key;
        $this->cache->delete($prefixedKey);
    }

    #[\Override]
    public function clear(): void
    {
        if (method_exists($this->cache, 'clear')) {
            $this->cache->clear();
        }
    }
}
