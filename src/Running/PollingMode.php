<?php

declare(strict_types=1);

namespace Tueen\Telegram\Running;

use Generator;
use Tueen\Telegram\Exceptions\TelegramException;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Custom\ArrayResult;
use Tueen\Telegram\Types\Error;
use Tueen\Telegram\Types\Update;

class PollingMode implements RunningModeInterface
{
    private int $offset = 0;
    private bool $stopped = false;

    public function __construct(
        private int $timeout = 30,
        private int $limit = 100,
        private ?array $allowedUpdates = null,
        private int $errorBackoffSeconds = 2
    ) {}

    public function setTimeout(int $timeout): static
    {
        $this->timeout = $timeout;
        return $this;
    }

    public function setLimit(int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    public function setAllowedUpdates(?array $allowedUpdates): static
    {
        $this->allowedUpdates = $allowedUpdates;
        return $this;
    }

    public function setOffset(int $offset): static
    {
        $this->offset = $offset;
        return $this;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function stop(): void
    {
        $this->stopped = true;
    }

    public function isStopped(): bool
    {
        return $this->stopped;
    }

    /**
     * Returns the first update of a batch, or null if empty.
     */
    #[\NoDiscard]
    public function getFirstUpdate(array $updates): ?Update
    {
        $first = array_first($updates);
        return $first instanceof Update ? $first : null;
    }

    /**
     * Returns the last update of a batch, or null if empty.
     */
    #[\NoDiscard]
    public function getLastUpdate(array $updates): ?Update
    {
        $last = array_last($updates);
        return $last instanceof Update ? $last : null;
    }

    /**
     * Runs continuous long-polling loop, dispatching updates to the handler.
     */
    public function processUpdate(Telegram $telegram, ?callable $handler = null): mixed
    {
        $this->stopped = false;

        foreach ($this->getUpdatesGenerator($telegram) as $update) {
            if ($handler !== null) {
                $handler($update);
            }

            if ($this->stopped) {
                break;
            }
        }

        return null;
    }

    /**
     * Update generator for manual iteration.
     *
     * @return Generator<Update>
     */
    public function getUpdatesGenerator(Telegram $telegram): Generator
    {
        while (!$this->stopped) {
            try {
                $response = $telegram->getUpdates(
                    offset: $this->offset,
                    limit: $this->limit,
                    timeout: $this->timeout,
                    allowedUpdates: $this->allowedUpdates
                );

                if ($response instanceof Error) {
                    if ($this->errorBackoffSeconds > 0) {
                        sleep($this->errorBackoffSeconds);
                    }
                    continue;
                }

                $updates = $response instanceof ArrayResult ? $response->all() : (array)$response;

                foreach ($updates as $update) {
                    if ($update instanceof Update) {
                        $this->offset = max($this->offset, $update->updateId + 1);
                        yield $update;
                    }
                }
            } catch (TelegramException $e) {
                if ($this->errorBackoffSeconds > 0) {
                    sleep($this->errorBackoffSeconds);
                }
            }
        }
    }
}
