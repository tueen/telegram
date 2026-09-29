<?php

declare(strict_types=1);

namespace Tueen\Telegram\Flow\Storage;

use Tueen\Telegram\Flow\FlowState;

/**
 * File-based state store storing serialized state JSON files.
 * Zero external dependencies, suitable for simple webhooks or local multi-process workers.
 */
class FileStateStore implements StateStoreInterface
{
    private string $directory;

    public function __construct(?string $directory = null)
    {
        $this->directory = rtrim($directory ?? sys_get_temp_dir() . '/tueen_flows', '/\\');
        if (!is_dir($this->directory)) {
            @mkdir($this->directory, 0777, true);
        }
    }

    private function getFilePath(string $key): string
    {
        $safeName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $key);
        $hash = substr(hash('xxh128', $key), 0, 12);
        return "{$this->directory}/flow_{$safeName}_{$hash}.json";
    }

    public function get(string $key): ?FlowState
    {
        $path = $this->getFilePath($key);
        if (!file_exists($path)) {
            return null;
        }

        $raw = @file_get_contents($path);
        if ($raw === false || $raw === '') {
            return null;
        }

        $data = json_decode($raw, true);
        if (!is_array($data)) {
            return null;
        }

        $state = FlowState::fromArray($data);
        if ($state->isExpired()) {
            @unlink($path);
            return null;
        }

        return $state;
    }

    public function set(string $key, FlowState $state, ?int $ttl = null): void
    {
        if ($ttl !== null && $ttl > 0) {
            $state->expiresAt = time() + $ttl;
        }
        $state->updatedAt = time();

        $path = $this->getFilePath($key);
        $json = json_encode($state->toArray(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $tempPath = $path . '.' . bin2hex(random_bytes(6)) . '.tmp';
        if (@file_put_contents($tempPath, $json, LOCK_EX) !== false) {
            @rename($tempPath, $path);
        } else {
            @file_put_contents($path, $json, LOCK_EX);
        }
    }

    public function delete(string $key): void
    {
        $path = $this->getFilePath($key);
        if (file_exists($path)) {
            @unlink($path);
        }
    }

    public function clear(): void
    {
        $files = glob("{$this->directory}/flow_*.json");
        if ($files !== false) {
            foreach ($files as $file) {
                @unlink($file);
            }
        }
    }
}
