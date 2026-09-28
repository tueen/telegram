<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Throwable;
use Tueen\Telegram\Attributes\Field;

/**
 * Represents an error response from Telegram Bot API or an internal client/network error.
 */
class Error extends Type
{
    #[Field('description', required: true)]
    private(set) string $description;

    #[Field('error_code', required: true)]
    private(set) int|string $errorCode;

    #[Field('parameters', required: false)]
    private(set) ?array $parameters = null;

    private(set) ?Throwable $exception = null;

    public function __construct(
        string $description,
        int|string $errorCode = 0,
        ?array $parameters = null,
        ?Throwable $exception = null
    ) {
        $this->description = $description;
        $this->errorCode = $errorCode;
        $this->parameters = $parameters;
        $this->exception = $exception;

        parent::__construct([
            'ok' => false,
            'error_code' => $errorCode,
            'description' => $description,
            'parameters' => $parameters,
        ]);
    }

    /**
     * Always returns false for Error objects.
     */
    #[\NoDiscard]
    public function ok(): bool
    {
        return false;
    }

    /**
     * Alias for ok().
     */
    #[\NoDiscard]
    public function isOk(): bool
    {
        return false;
    }

    /**
     * Factory from Telegram API response payload.
     */
    public static function fromResponse(array $response, ?Throwable $previous = null): self
    {
        $errorCode = $response['error_code'] ?? 0;
        $description = (string)($response['description'] ?? 'Unknown Telegram API Error');
        $parameters = $response['parameters'] ?? null;

        return new self($description, $errorCode, $parameters, $previous);
    }

    /**
     * Factory from any Throwable (cURL, network, runtime).
     * If exception has a positive integer code, it will be negated to indicate non-API internal error.
     */
    public static function fromThrowable(Throwable $e): self
    {
        $rawCode = $e->getCode();
        if (is_int($rawCode)) {
            $errorCode = $rawCode !== 0 ? -abs($rawCode) : -1;
        } else {
            $errorCode = !empty($rawCode) ? (string)$rawCode : -1;
        }

        return new self(
            description: $e->getMessage(),
            errorCode: $errorCode,
            parameters: null,
            exception: $e
        );
    }

    /**
     * Returns retry_after value if present (typically from 429 flood wait).
     */
    public function getRetryAfter(): ?int
    {
        if (isset($this->parameters['retry_after'])) {
            return (int)$this->parameters['retry_after'];
        }
        return null;
    }

    /**
     * Returns migrate_to_chat_id value if present (group migrated to supergroup).
     */
    public function getMigrateToChatId(): ?int
    {
        if (isset($this->parameters['migrate_to_chat_id'])) {
            return (int)$this->parameters['migrate_to_chat_id'];
        }
        return null;
    }

    public function toArray(): array
    {
        return [
            'ok' => false,
            'error_code' => $this->errorCode,
            'description' => $this->description,
            'parameters' => $this->parameters,
        ];
    }

    public function __toString(): string
    {
        return "[Error {$this->errorCode}] {$this->description}";
    }
}
