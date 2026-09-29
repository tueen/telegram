<?php

declare(strict_types=1);

namespace Tueen\Telegram\Running;

use Closure;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

/**
 * Adaptive Running Mode that dynamically switches between PollingMode (CLI/Daemon)
 * and WebhookMode (HTTP/Web Server) based on multi-vector environment detection.
 */
class AutoMode implements RunningModeInterface
{
    private PollingMode $pollingMode;
    private WebhookMode $webhookMode;
    private bool $autoDeleteWebhook;
    private bool $dropPendingUpdatesOnDelete;
    private ?Closure $detector = null;
    private ?bool $forcedCli = null;
    private ?RunningModeInterface $resolvedMode = null;

    /** @var list<callable(RunningModeInterface, string, Telegram): void> */
    private array $onModeResolvedCallbacks = [];

    public function __construct(
        ?PollingMode $pollingMode = null,
        ?WebhookMode $webhookMode = null,
        bool $autoDeleteWebhook = false,
        bool $dropPendingUpdatesOnDelete = false,
        ?callable $detector = null
    ) {
        $this->pollingMode = $pollingMode ?? new PollingMode();
        $this->webhookMode = $webhookMode ?? new WebhookMode();
        $this->autoDeleteWebhook = $autoDeleteWebhook;
        $this->dropPendingUpdatesOnDelete = $dropPendingUpdatesOnDelete;

        if ($detector !== null) {
            $this->detector = $detector(...);
        }
    }

    /**
     * Fluent factory builder.
     */
    #[\NoDiscard]
    public static function create(
        ?PollingMode $pollingMode = null,
        ?WebhookMode $webhookMode = null,
        bool $autoDeleteWebhook = false,
        bool $dropPendingUpdatesOnDelete = false,
        ?callable $detector = null
    ): self {
        return new self(
            pollingMode: $pollingMode,
            webhookMode: $webhookMode,
            autoDeleteWebhook: $autoDeleteWebhook,
            dropPendingUpdatesOnDelete: $dropPendingUpdatesOnDelete,
            detector: $detector
        );
    }

    public function setPollingMode(PollingMode $mode): static
    {
        $this->pollingMode = $mode;
        $this->resolvedMode = null;
        return $this;
    }

    public function getPollingMode(): PollingMode
    {
        return $this->pollingMode;
    }

    public function setWebhookMode(WebhookMode $mode): static
    {
        $this->webhookMode = $mode;
        $this->resolvedMode = null;
        return $this;
    }

    public function getWebhookMode(): WebhookMode
    {
        return $this->webhookMode;
    }

    public function setAutoDeleteWebhook(bool $autoDelete = true, bool $dropPendingUpdates = false): static
    {
        $this->autoDeleteWebhook = $autoDelete;
        $this->dropPendingUpdatesOnDelete = $dropPendingUpdates;
        return $this;
    }

    public function isAutoDeleteWebhook(): bool
    {
        return $this->autoDeleteWebhook;
    }

    public function setDetector(?callable $detector): static
    {
        $this->detector = $detector !== null ? $detector(...) : null;
        $this->resolvedMode = null;
        return $this;
    }

    /**
     * Force CLI (Polling) mode execution regardless of environment.
     */
    public function forceCli(bool $force = true): static
    {
        $this->forcedCli = $force ? true : null;
        $this->resolvedMode = null;
        return $this;
    }

    /**
     * Force HTTP (Webhook) mode execution regardless of environment.
     */
    public function forceHttp(bool $force = true): static
    {
        $this->forcedCli = $force ? false : null;
        $this->resolvedMode = null;
        return $this;
    }

    /**
     * Registers a callback executed when the active running mode is resolved.
     *
     * @param callable(RunningModeInterface $mode, string $modeType, Telegram $bot): void $callback
     */
    public function onModeResolved(callable $callback): static
    {
        $this->onModeResolvedCallbacks[] = $callback;
        return $this;
    }

    /**
     * Inspects multi-vector signals to detect if execution is within a CLI/terminal environment.
     */
    #[\NoDiscard]
    public function isCliEnvironment(): bool
    {
        if ($this->forcedCli !== null) {
            return $this->forcedCli;
        }

        if ($this->detector !== null) {
            return (bool)($this->detector)();
        }

        // 1. Explicit HTTP POST Request Indicator (covers async workers: RoadRunner, Swoole, FrankenPHP)
        if (isset($_SERVER['REQUEST_METHOD']) && strtoupper((string)$_SERVER['REQUEST_METHOD']) === 'POST') {
            return false;
        }

        // 2. Telegram Webhook Secret Token header present
        if (isset($_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'])) {
            return false;
        }

        // 3. Web server / gateway interface detected (Apache, FPM, CGI, FastCGI, LiteSpeed)
        $sapi = PHP_SAPI;
        if ($sapi !== 'cli' && $sapi !== 'phpdbg' && $sapi !== 'cli-server') {
            return false;
        }

        // 4. In built-in PHP web server (`php -S`), REQUEST_METHOD indicates an HTTP request
        if ($sapi === 'cli-server' && isset($_SERVER['REQUEST_METHOD'])) {
            return false;
        }

        // 5. Default CLI terminal environment
        return true;
    }

    /**
     * Resolves the active RunningModeInterface instance.
     */
    #[\NoDiscard]
    public function resolveActiveMode(Telegram $bot): RunningModeInterface
    {
        if ($this->resolvedMode !== null) {
            return $this->resolvedMode;
        }

        $isCli = $this->isCliEnvironment();
        $modeName = $isCli ? 'polling' : 'webhook';

        $this->resolvedMode = $isCli ? $this->pollingMode : $this->webhookMode;

        $logger = $bot->getConfig()->logger;
        if ($logger !== null) {
            $logger->info("AutoMode: Resolved active running mode to [{$modeName}] (PHP_SAPI: " . PHP_SAPI . ").");
        }

        foreach ($this->onModeResolvedCallbacks as $callback) {
            $callback($this->resolvedMode, $modeName, $bot);
        }

        return $this->resolvedMode;
    }

    /**
     * Executes update processing via the dynamically resolved active mode.
     */
    public function processUpdate(Telegram $bot, ?callable $handler = null): mixed
    {
        $activeMode = $this->resolveActiveMode($bot);

        // When switching to Polling, optionally clear any previously active Telegram webhook
        // to prevent Telegram Error 409 Conflict: can't use getUpdates while webhook is active.
        if ($activeMode instanceof PollingMode && $this->autoDeleteWebhook) {
            try {
                $bot->deleteWebhook(dropPendingUpdates: $this->dropPendingUpdatesOnDelete);
                $bot->getConfig()->logger?->info("AutoMode: Automatically deleted webhook before starting polling.");
            } catch (\Throwable $e) {
                $bot->getConfig()->logger?->warning("AutoMode: Could not delete webhook before polling: " . $e->getMessage());
            }
        }

        return $activeMode->processUpdate($bot, $handler);
    }

    /**
     * Proxies resolveUpdate() to WebhookMode.
     */
    public function resolveUpdate(Telegram $bot): Update
    {
        return $this->webhookMode->resolveUpdate($bot);
    }

    /**
     * Proxies safeResponse() to WebhookMode.
     */
    public function safeResponse(): void
    {
        $this->webhookMode->safeResponse();
    }

    /**
     * Proxies processPsrRequest() to WebhookMode.
     */
    public function processPsrRequest(ServerRequestInterface $request, Telegram $bot, mixed ...$handlers): ResponseInterface
    {
        $this->resolvedMode = $this->webhookMode;
        $bot->setRunningMode($this->webhookMode);
        return $this->webhookMode->processPsrRequest($request, $bot, ...$handlers);
    }
}
