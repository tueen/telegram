<?php

declare(strict_types=1);

namespace Tueen\Telegram\App;

use Throwable;
use Tueen\Telegram\App;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Error;

/**
 * Web-based Setup Wizard and Royal Management Dashboard for tueen/telegram apps.
 * Activated on HTTP GET requests to the bot entrypoint in web environments.
 */
class WebDashboard
{
    public function __construct(
        private readonly App $app
    ) {}

    /**
     * Handles incoming Web Dashboard requests (GET or dashboard action POST).
     */
    public function handle(): void
    {
        $action = $_REQUEST['action'] ?? null;
        $notice = null;
        $error = null;

        // Verify password protection if configured
        if (!$this->isAuthorized()) {
            $this->renderPasswordScreen();
            return;
        }

        // Process setup submission when token is empty
        if ($action === 'setup') {
            $token = trim((string)($_POST['bot_token'] ?? $_GET['bot_token'] ?? ''));
            $secret = trim((string)($_POST['secret_token'] ?? $_GET['secret_token'] ?? ''));
            $url = trim((string)($_POST['webhook_url'] ?? $_GET['webhook_url'] ?? ''));

            if (empty($token)) {
                $error = 'Bot token cannot be empty.';
            } elseif (!preg_match('/^\d{5,16}:[A-Za-z0-9_-]{30,}$/', $token)) {
                $error = 'Invalid Telegram bot token format. Bot tokens must match <id>:<secret>.';
            } else {
                try {
                    $testBot = new Telegram($token);
                    $me = $testBot->getMe();

                    if ($me instanceof Error || !$me->ok()) {
                        $error = 'Telegram API Error: ' . ($me instanceof Error ? $me->description : 'Invalid bot token.');
                    } else {
                        // Attempt to save to config.php
                        $this->saveConfig($token, $secret !== '' ? $secret : null, $url !== '' ? $url : null);

                        // Set webhook if URL provided
                        if (!empty($url)) {
                            $res = $testBot->setWebhook(url: $url, secretToken: $secret !== '' ? $secret : null);
                            if ($res instanceof Error || !$res->ok()) {
                                $notice = "Bot @{$me->username} verified, but setting webhook failed: " . ($res instanceof Error ? $res->description : 'Unknown error');
                            } else {
                                $notice = "🎉 Bot @{$me->username} successfully configured and webhook set!";
                            }
                        } else {
                            $notice = "🎉 Bot @{$me->username} successfully configured!";
                        }

                        // Reload app config and bot
                        $this->app->reloadConfig();
                    }
                } catch (Throwable $e) {
                    $error = 'Connection failed: ' . $e->getMessage();
                }
            }
        } elseif ($action === 'set_webhook') {
            $url = trim((string)($_POST['webhook_url'] ?? $_GET['webhook_url'] ?? $this->app->detectWebhookUrl()));
            $secret = $this->app->config['secret_token'] ?? null;
            $drop = !empty($_REQUEST['drop_pending']);

            try {
                $res = $this->app->bot()->setWebhook(
                    url: $url,
                    secretToken: $secret,
                    dropPendingUpdates: $drop
                );

                if ($res instanceof Error || !$res->ok()) {
                    $error = 'Failed to set webhook: ' . ($res instanceof Error ? $res->description : 'API Error');
                } else {
                    $notice = "Webhook successfully set to [{$url}]!";
                }
            } catch (Throwable $e) {
                $error = 'Error setting webhook: ' . $e->getMessage();
            }
        } elseif ($action === 'delete_webhook') {
            $drop = !empty($_REQUEST['drop_pending']);
            try {
                $res = $this->app->bot()->deleteWebhook(dropPendingUpdates: $drop);
                if ($res instanceof Error || !$res->ok()) {
                    $error = 'Failed to delete webhook: ' . ($res instanceof Error ? $res->description : 'API Error');
                } else {
                    $notice = "Webhook deleted successfully." . ($drop ? ' (Pending updates dropped)' : '');
                }
            } catch (Throwable $e) {
                $error = 'Error deleting webhook: ' . $e->getMessage();
            }
        }

        $this->renderDashboard($notice, $error);
    }

    /**
     * Checks if current request is authorized to view/use the dashboard.
     */
    private function isAuthorized(): bool
    {
        $password = $this->app->config['setup']['password'] ?? null;

        // If password is configured, require authentication
        if (!empty($password)) {
            if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
                @session_start();
            }

            if (isset($_POST['dashboard_password'])) {
                if (hash_equals((string)$password, (string)$_POST['dashboard_password'])) {
                    $_SESSION['tueen_dashboard_auth'] = true;
                    return true;
                }
            }

            return !empty($_SESSION['tueen_dashboard_auth']);
        }

        // If no password configured: allow only on local loopback / private IP,
        // or if explicitly permitted via config
        if (!empty($this->app->config['setup']['allow_unauthenticated'])) {
            return true;
        }

        $remote = $_SERVER['REMOTE_ADDR'] ?? '';
        $isLocal = in_array($remote, ['127.0.0.1', '::1', 'localhost', ''], true)
            || str_starts_with($remote, '192.168.')
            || str_starts_with($remote, '10.')
            || str_starts_with($remote, '172.');

        return $isLocal;
    }

    /**
     * Saves bot credentials into config.php.
     */
    private function saveConfig(string $token, ?string $secret, ?string $webhookUrl): bool
    {
        if (!preg_match('/^\d{5,16}:[A-Za-z0-9_-]{30,}$/', $token)) {
            throw new \InvalidArgumentException("Invalid bot token format. Telegram tokens must match <id>:<secret>.");
        }

        $configFile = $this->app->basePath . '/config.php';

        $tokenExport = var_export($token, true);
        $secretExport = var_export($secret, true);
        $urlExport = var_export($webhookUrl, true);

        $content = <<<PHP
<?php

declare(strict_types=1);

return [
    'token' => {$tokenExport},
    'secret_token' => {$secretExport},
    'webhook_url' => {$urlExport},
    'mode' => 'auto',
];

PHP;

        return @file_put_contents($configFile, $content, LOCK_EX) !== false;
    }

    /**
     * Counts active Flow session files in the flow storage directory.
     */
    private function countFlowSessions(): int
    {
        $dir = $this->app->flowStoragePath;
        if (!is_dir($dir)) {
            return 0;
        }
        $files = glob("{$dir}/flow_*.json");
        return $files !== false ? count($files) : 0;
    }

    /**
     * Renders password gate screen.
     */
    private function renderPasswordScreen(): void
    {
        $this->sendHtmlHeaders();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Tueen Dashboard - Authentication</title>
            <style><?= $this->getCss() ?></style>
        </head>
        <body>
            <div class="container" style="max-width: 440px; margin-top: 100px;">
                <div class="card header-card" style="text-align: center;">
                    <div class="logo">👑</div>
                    <h1 class="title">Tueen Telegram</h1>
                    <p class="subtitle">Enter dashboard password to continue</p>
                    <form method="POST" style="margin-top: 24px;">
                        <div class="form-group">
                            <input type="password" name="dashboard_password" class="input" placeholder="Password" required autofocus>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">Unlock Dashboard</button>
                    </form>
                </div>
            </div>
        </body>
        </html>
        <?php
        exit;
    }

    /**
     * Renders main dashboard or setup wizard.
     */
    private function renderDashboard(?string $notice = null, ?string $error = null): void
    {
        $token = $this->app->config['token'] ?? $this->app->config['bot_token'] ?? '';
        $isConfigured = !empty($token);

        $botInfo = null;
        $webhookInfo = null;
        $pingMs = null;

        if ($isConfigured) {
            try {
                $start = microtime(true);
                $botInfo = $this->app->bot()->getMe();
                $pingMs = round((microtime(true) - $start) * 1000, 1);
                $webhookInfo = $this->app->bot()->getWebhookInfo();
            } catch (Throwable $e) {
                $error ??= 'API Connection Error: ' . $e->getMessage();
            }
        }

        $detectedUrl = $this->app->detectWebhookUrl();
        $flowSessions = $this->countFlowSessions();

        $this->sendHtmlHeaders();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Tueen Telegram - <?= $isConfigured ? 'Dashboard' : 'Setup Wizard' ?></title>
            <style><?= $this->getCss() ?></style>
        </head>
        <body>
            <div class="container">
                <!-- Header -->
                <div class="card header-card">
                    <div class="header-content">
                        <div class="logo">👑</div>
                        <div>
                            <h1 class="title">Tueen Telegram</h1>
                            <p class="subtitle">The Royal Telegram Bot SDK • Bot API v<?= Telegram::BOT_API_VERSION ?></p>
                        </div>
                    </div>
                    <div>
                        <?php if ($isConfigured && $botInfo && $botInfo->ok()): ?>
                            <span class="badge badge-success">● Connected: @<?= htmlspecialchars($botInfo->username ?? 'Unknown') ?></span>
                        <?php else: ?>
                            <span class="badge badge-warning">● Setup Required</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Notifications -->
                <?php if ($notice): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($notice) ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <?php if (!$isConfigured): ?>
                    <!-- Setup Wizard Card -->
                    <div class="card">
                        <h2 class="card-title">🚀 First-Time Bot Setup</h2>
                        <p style="color: #94a3b8; font-size: 14px; margin-bottom: 20px;">
                            Configure your bot credentials below. You can obtain a bot token from <a href="https://t.me/BotFather" target="_blank" style="color: #818cf8; text-decoration: underline;">@BotFather</a> on Telegram.
                        </p>
                        <form method="POST" action="?action=setup">
                            <div class="form-group">
                                <label class="label">Bot Token (from @BotFather) *</label>
                                <input type="text" name="bot_token" class="input" placeholder="123456789:ABCdefGhIJKlmNoPQRsTUVwxyZ" required autofocus>
                            </div>
                            <div class="form-group">
                                <label class="label">Secret Token (Optional for Webhook Security)</label>
                                <input type="text" name="secret_token" id="secret_token" class="input" placeholder="Optional 1-256 character secret token">
                                <small style="display: block; color: #64748b; margin-top: 4px;">
                                    Telegram will send this in <code>X-Telegram-Bot-Api-Secret-Token</code> header for request authentication.
                                    <a href="javascript:void(0)" onclick="generateSecret()" style="color: #818cf8; margin-left: 8px;">Generate Random</a>
                                </small>
                            </div>
                            <div class="form-group">
                                <label class="label">Webhook URL (Auto-detected)</label>
                                <input type="url" name="webhook_url" class="input" value="<?= htmlspecialchars($detectedUrl) ?>" required>
                                <small style="display: block; color: #64748b; margin-top: 4px;">Must be a public HTTPS URL accessible by Telegram servers.</small>
                            </div>
                            <div style="margin-top: 24px;">
                                <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-size: 15px;">
                                    👑 Connect Bot & Set Webhook
                                </button>
                            </div>
                        </form>
                    </div>
                <?php else: ?>
                    <!-- Dashboard Grid -->
                    <div class="grid">
                        <!-- Bot Identity Card -->
                        <div class="card">
                            <h2 class="card-title">🤖 Bot Identity</h2>
                            <?php if ($botInfo && $botInfo->ok()): ?>
                                <table class="table">
                                    <tr>
                                        <td>Name</td>
                                        <td><strong><?= htmlspecialchars($botInfo->firstName) ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>Username</td>
                                        <td><a href="https://t.me/<?= htmlspecialchars($botInfo->username ?? '') ?>" target="_blank" style="color: #818cf8;">@<?= htmlspecialchars($botInfo->username ?? '') ?></a></td>
                                    </tr>
                                    <tr>
                                        <td>Bot ID</td>
                                        <td><code><?= htmlspecialchars((string)$botInfo->id) ?></code></td>
                                    </tr>
                                    <tr>
                                        <td>API Latency</td>
                                        <td><?= $pingMs !== null ? "{$pingMs} ms" : 'N/A' ?></td>
                                    </tr>
                                    <tr>
                                        <td>Groups Allowed</td>
                                        <td><?= $botInfo->canJoinGroups ? '✅ Yes' : '❌ No' ?></td>
                                    </tr>
                                </table>
                            <?php else: ?>
                                <p style="color: #ef4444;">Unable to fetch bot profile. Check token validity.</p>
                            <?php endif; ?>
                        </div>

                        <!-- Webhook Status Card -->
                        <div class="card">
                            <h2 class="card-title">🔗 Webhook Status</h2>
                            <?php if ($webhookInfo && $webhookInfo->ok()): ?>
                                <table class="table">
                                    <tr>
                                        <td>Active URL</td>
                                        <td style="word-break: break-all;">
                                            <?php if (!empty($webhookInfo->url)): ?>
                                                <code style="color: #34d399;"><?= htmlspecialchars($webhookInfo->url) ?></code>
                                            <?php else: ?>
                                                <span class="badge badge-warning">Not Set (Polling Mode or Inactive)</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Pending Updates</td>
                                        <td>
                                            <strong><?= $webhookInfo->pendingUpdateCount ?></strong>
                                            <?php if ($webhookInfo->pendingUpdateCount > 0): ?>
                                                <span style="color: #f59e0b; font-size: 12px; margin-left: 6px;">(Processing queue)</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Custom SSL Cert</td>
                                        <td><?= $webhookInfo->hasCustomCertificate ? 'Yes' : 'No (Standard CA)' ?></td>
                                    </tr>
                                    <?php if (!empty($webhookInfo->lastErrorMessage)): ?>
                                        <tr>
                                            <td>Last Error</td>
                                            <td style="color: #ef4444; font-size: 13px;">
                                                <strong><?= htmlspecialchars($webhookInfo->lastErrorMessage) ?></strong><br>
                                                <small style="color: #94a3b8;"><?= $webhookInfo->lastErrorDate ? date('Y-m-d H:i:s', $webhookInfo->lastErrorDate) : '' ?></small>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td>Max Connections</td>
                                        <td><?= $webhookInfo->maxConnections ?? 40 ?></td>
                                    </tr>
                                </table>
                            <?php else: ?>
                                <p style="color: #ef4444;">Unable to fetch webhook status.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Webhook Actions Card -->
                    <div class="card">
                        <h2 class="card-title">⚙️ Webhook Management</h2>
                        <form method="POST" action="?action=set_webhook" style="margin-bottom: 20px;">
                            <div class="form-group">
                                <label class="label">Webhook Target URL</label>
                                <div style="display: flex; gap: 8px;">
                                    <input type="url" name="webhook_url" class="input" value="<?= htmlspecialchars(!empty($webhookInfo?->url) ? $webhookInfo->url : $detectedUrl) ?>" required style="flex: 1;">
                                    <button type="submit" class="btn btn-primary">🎯 Set / Update Webhook</button>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px; margin-top: 8px;">
                                <label style="font-size: 13px; color: #94a3b8; display: flex; align-items: center; gap: 6px; cursor: pointer;">
                                    <input type="checkbox" name="drop_pending" value="1"> Drop pending updates when setting webhook
                                </label>
                            </div>
                        </form>

                        <div style="display: flex; flex-wrap: wrap; gap: 12px; border-top: 1px solid #334155; padding-top: 16px;">
                            <form method="POST" action="?action=delete_webhook" onsubmit="return confirm('Delete active webhook? The bot will stop receiving updates via HTTP.');">
                                <button type="submit" class="btn btn-danger">🗑️ Delete Webhook</button>
                            </form>
                            <form method="POST" action="?action=delete_webhook&drop_pending=1" onsubmit="return confirm('Delete webhook and drop all pending updates?');">
                                <button type="submit" class="btn btn-danger" style="background: #7f1d1d;">🗑️ Delete & Drop Updates</button>
                            </form>
                            <a href="<?= htmlspecialchars($_SERVER['SCRIPT_NAME'] ?? '') ?>" class="btn btn-secondary">🔄 Refresh Data</a>
                        </div>
                    </div>

                    <!-- Flow Sessions & Environment Card -->
                    <div class="grid">
                        <div class="card">
                            <h2 class="card-title">💾 Storage & Flow Cache</h2>
                            <table class="table">
                                <tr>
                                    <td>Flow Storage Path</td>
                                    <td><code><?= htmlspecialchars($this->app->flowStoragePath) ?></code></td>
                                </tr>
                                <tr>
                                    <td>Active Flow Sessions</td>
                                    <td>
                                        <span class="badge badge-info"><?= $flowSessions ?> active session<?= $flowSessions !== 1 ? 's' : '' ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Storage Security</td>
                                    <td><span style="color: #34d399;">🔒 Protected (.htaccess & index.php)</span></td>
                                </tr>
                            </table>
                        </div>

                        <div class="card">
                            <h2 class="card-title">🖥️ Server Environment</h2>
                            <table class="table">
                                <tr>
                                    <td>PHP SAPI / Version</td>
                                    <td><?= PHP_SAPI ?> • PHP <?= PHP_VERSION ?></td>
                                </tr>
                                <tr>
                                    <td>Project Root</td>
                                    <td><code><?= htmlspecialchars($this->app->basePath) ?></code></td>
                                </tr>
                                <tr>
                                    <td>Detected Request URL</td>
                                    <td style="word-break: break-all;"><code><?= htmlspecialchars($detectedUrl) ?></code></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Diagnostics Card -->
                    <div class="card">
                        <h2 class="card-title">🩺 System Diagnostics (Doctor)</h2>
                        <table class="table">
                            <?php foreach (\Tueen\Telegram\App\Doctor::diagnose($this->app) as $check): ?>
                                <tr>
                                    <td><?= htmlspecialchars($check['title']) ?></td>
                                    <td>
                                        <span class="badge badge-<?= $check['status'] === 'ok' ? 'success' : ($check['status'] === 'warning' ? 'warning' : 'danger') ?>" style="margin-right: 8px;">
                                            <?= strtoupper($check['status']) ?>
                                        </span>
                                        <?= htmlspecialchars($check['message']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                <?php endif; ?>

                <div class="footer">
                    Powered by <strong style="color: #f1f5f9;">Tueen Telegram</strong> • Slogan: <em>"The Royal Telegram Bot SDK for Modern PHP"</em>
                </div>
            </div>

            <script>
                function generateSecret() {
                    const arr = new Uint8Array(24);
                    window.crypto.getRandomValues(arr);
                    const hex = Array.from(arr, b => b.toString(16).padStart(2, '0')).join('');
                    document.getElementById('secret_token').value = hex;
                }
            </script>
        </body>
        </html>
        <?php
        exit;
    }

    private function sendHtmlHeaders(): void
    {
        if (!headers_sent()) {
            http_response_code(200);
            header('Content-Type: text/html; charset=UTF-8');
            header('X-Robots-Tag: noindex, nofollow, noarchive');
        }
    }

    private function getCss(): string
    {
        return <<<CSS
            * { box-sizing: border-box; margin: 0; padding: 0; }
            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                background-color: #0b0f19;
                color: #e2e8f0;
                line-height: 1.5;
                padding: 24px 16px;
            }
            .container { max-width: 900px; margin: 0 auto; }
            .card {
                background: #1e293b;
                border: 1px solid #334155;
                border-radius: 12px;
                padding: 24px;
                margin-bottom: 20px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2), 0 2px 4px -2px rgba(0, 0, 0, 0.2);
            }
            .header-card {
                background: linear-gradient(135deg, #1e1b4b 0%, #0f172a 100%);
                border-color: #4338ca;
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 16px;
            }
            .header-content { display: flex; align-items: center; gap: 16px; }
            .logo { font-size: 38px; line-height: 1; }
            .title { font-size: 22px; font-weight: 700; color: #f8fafc; letter-spacing: -0.025em; }
            .subtitle { font-size: 13px; color: #94a3b8; }
            .card-title { font-size: 16px; font-weight: 600; color: #f1f5f9; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
            .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; }
            .badge {
                display: inline-flex;
                align-items: center;
                font-size: 12px;
                font-weight: 600;
                padding: 4px 10px;
                border-radius: 9999px;
            }
            .badge-success { background: #064e3b; color: #34d399; border: 1px solid #059669; }
            .badge-warning { background: #78350f; color: #fbbf24; border: 1px solid #d97706; }
            .badge-danger { background: #7f1d1d; color: #f87171; border: 1px solid #dc2626; }
            .badge-info { background: #1e3a8a; color: #60a5fa; border: 1px solid #2563eb; }
            .alert {
                padding: 12px 16px;
                border-radius: 8px;
                font-size: 14px;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .alert-success { background: #064e3b; border: 1px solid #059669; color: #a7f3d0; }
            .alert-danger { background: #7f1d1d; border: 1px solid #dc2626; color: #fecaca; }
            .form-group { margin-bottom: 16px; }
            .label { display: block; font-size: 13px; font-weight: 500; color: #cbd5e1; margin-bottom: 6px; }
            .input {
                width: 100%;
                background: #0f172a;
                border: 1px solid #334155;
                color: #f8fafc;
                padding: 10px 14px;
                border-radius: 8px;
                font-size: 14px;
                font-family: inherit;
                outline: none;
                transition: border-color 0.15s ease;
            }
            .input:focus { border-color: #6366f1; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2); }
            .btn {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                font-size: 13px;
                font-weight: 600;
                padding: 8px 16px;
                border-radius: 8px;
                cursor: pointer;
                border: none;
                text-decoration: none;
                transition: all 0.15s ease;
            }
            .btn-primary { background: #4f46e5; color: #ffffff; }
            .btn-primary:hover { background: #4338ca; }
            .btn-secondary { background: #334155; color: #f8fafc; }
            .btn-secondary:hover { background: #475569; }
            .btn-danger { background: #dc2626; color: #ffffff; }
            .btn-danger:hover { background: #b91c1c; }
            .table { width: 100%; border-collapse: collapse; font-size: 13px; }
            .table tr { border-bottom: 1px solid #334155; }
            .table tr:last-child { border-bottom: none; }
            .table td { padding: 10px 0; color: #cbd5e1; }
            .table td:first-child { width: 130px; color: #94a3b8; font-weight: 500; }
            code {
                background: #0f172a;
                border: 1px solid #334155;
                padding: 2px 6px;
                border-radius: 4px;
                font-size: 12px;
                font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
                color: #e2e8f0;
            }
            .footer { text-align: center; font-size: 12px; color: #64748b; margin-top: 32px; }
CSS;
    }
}
