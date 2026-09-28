<?php

declare(strict_types=1);

namespace Tueen\Telegram\Flow;

use JsonSerializable;

/**
 * Value object representing the serialized state of an active Flow.
 */
final class FlowState implements JsonSerializable
{
    /**
     * @param class-string<Flow> $flowClass
     * @param string $currentStep
     * @param list<string> $history
     * @param array<string, mixed> $data
     * @param int $createdAt
     * @param int $updatedAt
     * @param int|null $expiresAt
     */
    public function __construct(
        public string $flowClass,
        public string $currentStep = 'start',
        public array $history = [],
        public array $data = [],
        public int $createdAt = 0,
        public int $updatedAt = 0,
        public ?int $expiresAt = null,
    ) {
        $now = time();
        if ($this->createdAt === 0) {
            $this->createdAt = $now;
        }
        if ($this->updatedAt === 0) {
            $this->updatedAt = $now;
        }
    }

    /**
     * Checks if the state has expired according to its expiresAt timestamp.
     */
    public function isExpired(): bool
    {
        return $this->expiresAt !== null && time() > $this->expiresAt;
    }

    /**
     * Creates an instance from a plain associative array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            flowClass: (string) ($data['flow_class'] ?? $data['flowClass'] ?? ''),
            currentStep: (string) ($data['current_step'] ?? $data['currentStep'] ?? 'start'),
            history: (array) ($data['history'] ?? []),
            data: (array) ($data['data'] ?? []),
            createdAt: (int) ($data['created_at'] ?? $data['createdAt'] ?? time()),
            updatedAt: (int) ($data['updated_at'] ?? $data['updatedAt'] ?? time()),
            expiresAt: isset($data['expires_at']) ? (int) $data['expires_at'] : (isset($data['expiresAt']) ? (int) $data['expiresAt'] : null),
        );
    }

    /**
     * Serializes into an associative array.
     */
    public function toArray(): array
    {
        return [
            'flow_class' => $this->flowClass,
            'current_step' => $this->currentStep,
            'history' => $this->history,
            'data' => $this->data,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'expires_at' => $this->expiresAt,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
