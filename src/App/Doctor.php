<?php

declare(strict_types=1);

namespace Tueen\Telegram\App;

use Throwable;
use Tueen\Telegram\App;
use Tueen\Telegram\Types\Error;

/**
 * System diagnostics and self-test utility for Telegram bot runtime environments.
 */
class Doctor
{
    /**
     * Executes all diagnostic health checks.
     *
     * @return list<array{title: string, status: 'ok'|'warning'|'error', message: string}>
     */
    public static function diagnose(App $app): array
    {
        $checks = [];

        // 1. PHP Version
        $phpVersion = PHP_VERSION;
        if (version_compare($phpVersion, '8.4.0', '>=')) {
            $checks[] = [
                'title' => 'PHP Version',
                'status' => 'ok',
                'message' => "PHP {$phpVersion} (Supports Property Hooks & Asymmetric Visibility)",
            ];
        } else {
            $checks[] = [
                'title' => 'PHP Version',
                'status' => 'error',
                'message' => "PHP {$phpVersion} detected. tueen/telegram requires PHP 8.4+.",
            ];
        }

        // 2. Required PHP Extensions
        $requiredExtensions = ['json', 'curl', 'mbstring', 'openssl'];
        $missing = array_filter($requiredExtensions, fn(string $ext) => !extension_loaded($ext));
        if (empty($missing)) {
            $checks[] = [
                'title' => 'PHP Extensions',
                'status' => 'ok',
                'message' => 'All required extensions are loaded (' . implode(', ', $requiredExtensions) . ')',
            ];
        } else {
            $checks[] = [
                'title' => 'PHP Extensions',
                'status' => 'error',
                'message' => 'Missing required extensions: ' . implode(', ', $missing),
            ];
        }

        // 3. Storage Directory & Permissions
        $storageDir = $app->storagePath;
        $flowDir = $app->flowStoragePath;

        if (is_dir($flowDir) && is_writable($flowDir)) {
            $checks[] = [
                'title' => 'Flow Storage',
                'status' => 'ok',
                'message' => "Writable at [{$flowDir}]",
            ];
        } elseif (!is_dir($flowDir)) {
            $checks[] = [
                'title' => 'Flow Storage',
                'status' => 'error',
                'message' => "Directory does not exist: [{$flowDir}]",
            ];
        } else {
            $checks[] = [
                'title' => 'Flow Storage',
                'status' => 'error',
                'message' => "Directory is not writable: [{$flowDir}]. Check file permissions.",
            ];
        }

        // 4. Telegram Bot API Connectivity & Token
        $token = $app->config['token'] ?? $app->config['bot_token'] ?? null;
        if (empty($token)) {
            $checks[] = [
                'title' => 'Bot Token',
                'status' => 'warning',
                'message' => 'Bot token is not configured yet (Setup mode active).',
            ];
        } elseif ($token === 'TEST_TOKEN' || str_starts_with($token, 'FAKE_')) {
            $checks[] = [
                'title' => 'Telegram Bot API',
                'status' => 'ok',
                'message' => "Mock test token [{$token}] active (network call bypassed).",
            ];
        } else {
            try {
                $start = microtime(true);
                $me = $app->bot()->getMe();
                $latencyMs = round((microtime(true) - $start) * 1000, 1);

                if ($me instanceof Error || !$me->ok()) {
                    $checks[] = [
                        'title' => 'Telegram Bot API',
                        'status' => 'error',
                        'message' => 'API Error: ' . ($me instanceof Error ? $me->description : 'Unable to verify token.'),
                    ];
                } else {
                    $checks[] = [
                        'title' => 'Telegram Bot API',
                        'status' => 'ok',
                        'message' => "Connected to @{$me->username} (Latency: {$latencyMs}ms)",
                    ];
                }
            } catch (Throwable $e) {
                $checks[] = [
                    'title' => 'Telegram Bot API',
                    'status' => 'error',
                    'message' => 'Connection failed: ' . $e->getMessage() . '. Check internet/firewall/proxy.',
                ];
            }
        }

        // 5. Webhook URL Compliance
        $webhookUrl = $app->detectWebhookUrl();
        if (str_starts_with($webhookUrl, 'https://')) {
            $checks[] = [
                'title' => 'Webhook URL',
                'status' => 'ok',
                'message' => "Complies with HTTPS requirement: {$webhookUrl}",
            ];
        } else {
            $checks[] = [
                'title' => 'Webhook URL',
                'status' => 'warning',
                'message' => "Detected non-HTTPS URL ({$webhookUrl}). Telegram requires HTTPS for Webhooks.",
            ];
        }

        return $checks;
    }

    /**
     * Prints diagnostics table to the CLI terminal.
     */
    public static function printCli(App $app): int
    {
        $checks = self::diagnose($app);
        $hasError = false;

        echo "\n";
        echo "  \033[1;35m🩺 Tueen Telegram System Diagnostics (Doctor)\033[0m\n";
        echo "  ===============================================================\n";

        foreach ($checks as $check) {
            $badge = match ($check['status']) {
                'ok' => "\033[32m[PASS]\033[0m",
                'warning' => "\033[33m[WARN]\033[0m",
                'error' => "\033[31m[FAIL]\033[0m",
            };

            if ($check['status'] === 'error') {
                $hasError = true;
            }

            $title = str_pad($check['title'], 20);
            echo "  {$badge} {$title} : {$check['message']}\n";
        }

        echo "  ===============================================================\n\n";

        return $hasError ? 1 : 0;
    }
}
