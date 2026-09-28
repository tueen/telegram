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
    private ?\Closure $processDispatcher = null;
    /** @var array<int, bool> */
    private array $childPids = [];

    public function __construct(
        private int $timeout = 30,
        private int $limit = 100,
        private ?array $allowedUpdates = null,
        private int $errorBackoffSeconds = 2,
        private bool $forkProcess = false,
        ?callable $processDispatcher = null,
        private int $maxForkWorkers = 16
    ) {
        if ($processDispatcher !== null) {
            $this->processDispatcher = $processDispatcher(...);
        }
    }

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

    public function setForkProcess(bool $forkProcess): static
    {
        $this->forkProcess = $forkProcess;
        return $this;
    }

    public function forkProcess(bool $enable = true): static
    {
        $this->forkProcess = $enable;
        return $this;
    }

    public function isForkProcess(): bool
    {
        return $this->forkProcess;
    }

    public function setProcessDispatcher(?callable $dispatcher): static
    {
        $this->processDispatcher = $dispatcher !== null ? $dispatcher(...) : null;
        return $this;
    }

    public function getProcessDispatcher(): ?\Closure
    {
        return $this->processDispatcher;
    }

    /**
     * Returns the first update of a batch, or null if empty.
     */
    #[\NoDiscard]
    public function getFirstUpdate(array $updates): ?Update
    {
        $first = function_exists('array_first')
            ? array_first($updates)
            : (!empty($updates) ? $updates[array_key_first($updates)] : null);
        return $first instanceof Update ? $first : null;
    }

    /**
     * Returns the last update of a batch, or null if empty.
     */
    #[\NoDiscard]
    public function getLastUpdate(array $updates): ?Update
    {
        $last = function_exists('array_last')
            ? array_last($updates)
            : (!empty($updates) ? $updates[array_key_last($updates)] : null);
        return $last instanceof Update ? $last : null;
    }

    /**
     * Runs continuous long-polling loop, dispatching updates to the handler.
     */
    public function processUpdate(Telegram $telegram, ?callable $handler = null): mixed
    {
        $this->stopped = false;

        if (function_exists('pcntl_signal')) {
            pcntl_signal(SIGINT, function () {
                $this->stop();
            });
            pcntl_signal(SIGTERM, function () {
                $this->stop();
            });
        }

        while (!$this->stopped) {
            if (function_exists('pcntl_signal_dispatch')) {
                pcntl_signal_dispatch();
            }

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
                    if (!$update instanceof Update) {
                        continue;
                    }

                    $this->offset = max($this->offset, $update->updateId + 1);

                    if ($this->processDispatcher !== null) {
                        ($this->processDispatcher)($update, $telegram, fn() => $this->dispatchUpdate($telegram, $update, $handler));
                    } elseif ($this->forkProcess) {
                        $this->forkAndDispatch($telegram, $update, $handler);
                    } else {
                        $this->dispatchUpdate($telegram, $update, $handler);
                    }

                    if ($this->stopped) {
                        break 2;
                    }
                }
            } catch (TelegramException $e) {
                if ($this->errorBackoffSeconds > 0) {
                    sleep($this->errorBackoffSeconds);
                }
            }
        }

        return null;
    }

    private function dispatchUpdate(Telegram $telegram, Update $update, ?callable $handler): void
    {
        $telegram->setUpdate($update);

        if ($handler !== null) {
            $handler($update);
        }
    }

    public function setMaxForkWorkers(int $maxForkWorkers): static
    {
        $this->maxForkWorkers = max(1, $maxForkWorkers);
        return $this;
    }

    public function maxForkWorkers(int $maxForkWorkers): static
    {
        return $this->setMaxForkWorkers($maxForkWorkers);
    }

    public function getMaxForkWorkers(): int
    {
        return $this->maxForkWorkers;
    }

    private function forkAndDispatch(Telegram $telegram, Update $update, ?callable $handler): void
    {
        if (!function_exists('pcntl_fork')) {
            $this->dispatchUpdate($telegram, $update, $handler);
            return;
        }

        $this->reapChildProcesses();

        // Enforce maximum concurrent worker limit
        while (count($this->childPids) >= $this->maxForkWorkers) {
            if (function_exists('pcntl_wait')) {
                $status = 0;
                $exitedPid = pcntl_wait($status);
                if ($exitedPid > 0) {
                    unset($this->childPids[$exitedPid]);
                }
            } else {
                break;
            }
        }

        $pid = pcntl_fork();

        if ($pid === -1) {
            // Failed to fork, execute synchronously
            $this->dispatchUpdate($telegram, $update, $handler);
            return;
        }

        if ($pid === 0) {
            // Child process
            try {
                $this->dispatchUpdate($telegram, $update, $handler);
            } catch (\Throwable $e) {
                if ($telegram->getConfig()->logger !== null) {
                    $telegram->getConfig()->logger->error("Error handling update in child process: " . $e->getMessage(), ['exception' => $e]);
                }
            } finally {
                if (function_exists('posix__exit')) {
                    posix__exit(0);
                } else {
                    exit(0);
                }
            }
        }

        // Parent process: register active child PID
        $this->childPids[$pid] = true;
        $this->reapChildProcesses();
    }

    private function reapChildProcesses(): void
    {
        if (!function_exists('pcntl_waitpid') || empty($this->childPids)) {
            return;
        }

        $status = 0;
        while (($reaped = pcntl_waitpid(-1, $status, WNOHANG)) > 0) {
            unset($this->childPids[$reaped]);
        }
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
                        $telegram->setUpdate($update);
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
