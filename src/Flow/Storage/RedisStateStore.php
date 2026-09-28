<?php

declare(strict_types=1);

namespace Tueen\Telegram\Flow\Storage;

use Tueen\Telegram\Exceptions\TelegramException;
use Tueen\Telegram\Flow\FlowState;

/**
 * Production-ready Redis state storage driver for Tueen Flow sessions.
 *
 * Supports native \Redis extension and Predis\Client instances.
 */
class RedisStateStore implements StateStoreInterface
{
    /**
     * @param object $client Native \Redis or Predis\Client instance
     * @param string $prefix Key prefix for namespace isolation
     */
    public function __construct(
        private readonly object $client,
        private readonly string $prefix = 'tueen:flow:'
    ) {}

    public function get(string $key): ?FlowState
    {
        $prefixedKey = $this->prefix . $key;
        $raw = $this->client->get($prefixedKey);

        if ($raw === false || $raw === null || $raw === '') {
            return null;
        }

        $decoded = json_decode((string) $raw, true);
        if (!is_array($decoded)) {
            return null;
        }

        $state = FlowState::fromArray($decoded);
        if ($state->isExpired()) {
            $this->delete($key);
            return null;
        }

        return $state;
    }

    public function set(string $key, FlowState $state, ?int $ttl = null): void
    {
        $prefixedKey = $this->prefix . $key;

        if ($ttl !== null && $ttl > 0) {
            $state->expiresAt = time() + $ttl;
        }
        $state->updatedAt = time();

        $serialized = json_encode($state->toArray(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($ttl !== null && $ttl > 0) {
            if (method_exists($this->client, 'setex')) {
                $this->client->setex($prefixedKey, $ttl, $serialized);
            } else {
                $this->client->set($prefixedKey, $serialized, 'EX', $ttl);
            }
        } else {
            $this->client->set($prefixedKey, $serialized);
        }
    }

    public function delete(string $key): void
    {
        $prefixedKey = $this->prefix . $key;
        if (method_exists($this->client, 'del')) {
            $this->client->del($prefixedKey);
        } else {
            $this->client->delete($prefixedKey);
        }
    }

    public function clear(): void
    {
        // Find and delete all keys matching the prefix
        $pattern = $this->prefix . '*';
        $keys = [];

        if (method_exists($this->client, 'keys')) {
            $keys = $this->client->keys($pattern);
        }

        if (!empty($keys) && is_array($keys)) {
            foreach ($keys as $k) {
                if (method_exists($this->client, 'del')) {
                    $this->client->del($k);
                } else {
                    $this->client->delete($k);
                }
            }
        }
    }
}
