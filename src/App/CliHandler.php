<?php

declare(strict_types=1);

namespace Tueen\Telegram\App;

use Throwable;
use Tueen\Telegram\App;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Error;

/**
 * Handles CLI commands for tueen/telegram apps (polling, webhook management, bot info).
 */
class CliHandler
{
    public function __construct(
        private readonly App $app
    ) {}

    /**
     * Handles command-line arguments and actions.
     *
     * @param list<mixed> $handlers Handlers to pass when running polling
     */
    public function handle(array $handlers = []): mixed
    {
        global $argv, $argc;

        $args = $argv ?? [];
        $command = $args[1] ?? 'poll';

        return match ($command) {
            'webhook:set' => $this->setWebhook($args[2] ?? null, in_array('--drop', $args, true)),
            'webhook:delete' => $this->deleteWebhook(in_array('--drop', $args, true)),
            'webhook:info' => $this->webhookInfo(),
            'bot:info', 'me' => $this->botInfo(),
            'doctor', 'check' => Doctor::printCli($this->app),
            'help', '--help', '-h' => $this->showHelp(),
            default => $this->startPolling($handlers),
        };
    }

    private function setWebhook(?string $url, bool $dropPending): int
    {
        $targetUrl = $url ?? $this->app->config['webhook_url'] ?? null;
        if (empty($targetUrl)) {
            $this->error("Webhook URL is required. Provide as argument or configure 'webhook_url' in config.php.");
            $this->line("Usage: php index.php webhook:set https://yourdomain.com/index.php [--drop]");
            return 1;
        }

        $secretToken = $this->app->config['secret_token'] ?? null;

        $this->info("Setting webhook to: {$targetUrl}...");
        try {
            $res = $this->app->bot()->setWebhook(
                url: $targetUrl,
                secretToken: $secretToken,
                dropPendingUpdates: $dropPending
            );

            if ($res instanceof Error || !$res->ok()) {
                $this->error("Failed to set webhook: " . ($res instanceof Error ? $res->description : 'API Error'));
                return 1;
            }

            $this->success("Webhook successfully configured!");
            return 0;
        } catch (Throwable $e) {
            $this->error("Exception setting webhook: " . $e->getMessage());
            return 1;
        }
    }

    private function deleteWebhook(bool $dropPending): int
    {
        $this->info("Deleting webhook" . ($dropPending ? " (dropping pending updates)..." : "..."));
        try {
            $res = $this->app->bot()->deleteWebhook(dropPendingUpdates: $dropPending);
            if ($res instanceof Error || !$res->ok()) {
                $this->error("Failed to delete webhook: " . ($res instanceof Error ? $res->description : 'API Error'));
                return 1;
            }

            $this->success("Webhook deleted successfully.");
            return 0;
        } catch (Throwable $e) {
            $this->error("Exception deleting webhook: " . $e->getMessage());
            return 1;
        }
    }

    private function webhookInfo(): int
    {
        $this->info("Fetching webhook info from Telegram Bot API...");
        try {
            $info = $this->app->bot()->getWebhookInfo();
            if ($info instanceof Error || !$info->ok()) {
                $this->error("Failed to get webhook info: " . ($info instanceof Error ? $info->description : 'API Error'));
                return 1;
            }

            $this->line("");
            $this->line("  \033[1;35mWebhook Information:\033[0m");
            $this->line("  ---------------------------------------------");
            $this->line("  URL:                  " . ($info->url ?: "\033[33m(Not Set)\033[0m"));
            $this->line("  Pending Updates:      {$info->pendingUpdateCount}");
            $this->line("  Custom Certificate:   " . ($info->hasCustomCertificate ? 'Yes' : 'No'));
            $this->line("  Max Connections:      " . ($info->maxConnections ?? 40));
            if (!empty($info->ipAddress)) {
                $this->line("  IP Address:           {$info->ipAddress}");
            }
            if (!empty($info->lastErrorMessage)) {
                $this->line("  \033[31mLast Error:\033[0m           {$info->lastErrorMessage}");
                if ($info->lastErrorDate) {
                    $this->line("  \033[31mLast Error Date:\033[0m      " . date('Y-m-d H:i:s', $info->lastErrorDate));
                }
            }
            $this->line("");
            return 0;
        } catch (Throwable $e) {
            $this->error("Exception: " . $e->getMessage());
            return 1;
        }
    }

    private function botInfo(): int
    {
        $this->info("Fetching bot identity from Telegram Bot API...");
        try {
            $me = $this->app->bot()->getMe();
            if ($me instanceof Error || !$me->ok()) {
                $this->error("Failed to fetch bot profile: " . ($me instanceof Error ? $me->description : 'API Error'));
                return 1;
            }

            $this->line("");
            $this->line("  \033[1;36mBot Identity:\033[0m");
            $this->line("  ---------------------------------------------");
            $this->line("  ID:                   {$me->id}");
            $this->line("  Name:                 {$me->firstName}");
            $this->line("  Username:             @" . ($me->username ?? 'N/A'));
            $this->line("  Can Join Groups:      " . ($me->canJoinGroups ? 'Yes' : 'No'));
            $this->line("  Can Read Messages:    " . ($me->canReadAllGroupMessages ? 'Yes' : 'No'));
            $this->line("  Supports Inline:      " . ($me->supportsInlineQueries ? 'Yes' : 'No'));
            $this->line("");
            return 0;
        } catch (Throwable $e) {
            $this->error("Exception: " . $e->getMessage());
            return 1;
        }
    }

    private function showHelp(): int
    {
        $this->line("");
        $this->line("  \033[1;33m👑 Tueen Telegram CLI Runner\033[0m");
        $this->line("  ---------------------------------------------");
        $this->line("  \033[32mphp <script.php>\033[0m                     Start long-polling mode");
        $this->line("  \033[32mphp <script.php> webhook:set <url>\033[0m   Set Telegram webhook URL");
        $this->line("  \033[32mphp <script.php> webhook:delete\033[0m      Delete Telegram webhook");
        $this->line("  \033[32mphp <script.php> webhook:info\033[0m        Inspect current webhook status");
        $this->line("  \033[32mphp <script.php> bot:info\033[0m            Display bot profile from getMe");
        $this->line("  \033[32mphp <script.php> doctor\033[0m              Run system diagnostics & health checks");
        $this->line("");
        return 0;
    }

    /**
     * @param list<mixed> $handlers
     */
    private function startPolling(array $handlers): mixed
    {
        $bot = $this->app->bot();

        // Print welcome banner
        $this->line("");
        $this->line("  \033[1;33m👑 Tueen Telegram Bot API Client v" . Telegram::BOT_API_VERSION . "\033[0m");
        $this->line("  Mode:    \033[1;32mLong-Polling\033[0m");
        $this->line("  Storage: \033[36m" . $this->app->flowStoragePath . "\033[0m");

        try {
            $me = $bot->getMe();
            if ($me->ok()) {
                $this->line("  Bot:     \033[1;37m{$me->firstName}\033[0m (@{$me->username}) [ID: {$me->id}]");
            }
        } catch (Throwable) {
            // Non-blocking in polling start
        }

        $this->line("  \033[90mListening for updates... Press Ctrl+C to stop.\033[0m");
        $this->line("");

        // Switch to polling mode and run
        $bot->usePollingMode();
        return $bot->run(...$handlers);
    }

    private function line(string $text): void
    {
        echo "{$text}\n";
    }

    private function info(string $text): void
    {
        echo "\033[34m[INFO]\033[0m {$text}\n";
    }

    private function success(string $text): void
    {
        echo "\033[32m[OK]\033[0m {$text}\n";
    }

    private function error(string $text): void
    {
        echo "\033[31m[ERROR]\033[0m {$text}\n";
    }
}
