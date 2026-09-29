<?php

declare(strict_types=1);

namespace Tueen\Telegram;

use Closure;
use Tueen\Telegram\App\CliHandler;
use Tueen\Telegram\App\ControllerDiscovery;
use Tueen\Telegram\App\Doctor;
use Tueen\Telegram\App\Env;
use Tueen\Telegram\App\WebDashboard;
use Tueen\Telegram\Flow\FlowManager;
use Tueen\Telegram\Flow\Storage\FileStateStore;
use Tueen\Telegram\Pipeline\MiddlewareInterface;
use Tueen\Telegram\Routing\Router;
use Tueen\Telegram\Running\AutoMode;
use Tueen\Telegram\Running\PollingMode;
use Tueen\Telegram\Running\WebhookMode;

/**
 * High-level application bootstrapper and orchestrator for tueen/telegram.
 *
 * Provides effortless zero-config project setup, auto environment detection,
 * file-based flow storage defaults, route loading, and web setup wizard.
 *
 * @mixin \Tueen\Telegram\Contracts\TelegramMethods
 */
class App
{
    /** Project base root directory. */
    private(set) string $basePath;

    /** Loaded configuration array. */
    private(set) array $config = [];

    /** Underlying Telegram client facade. */
    private(set) Telegram $bot;

    /** Directory path for application storage. */
    private(set) string $storagePath;

    /** Directory path for conversation flow state persistence. */
    private(set) string $flowStoragePath;

    /**
     * Initializes the App instance with the given project base path.
     *
     * @param string $basePath Root directory of the bot project
     * @param array $config Optional configuration overrides
     */
    public function __construct(string $basePath, array $config = [])
    {
        $this->basePath = realpath($basePath) ?: rtrim($basePath, '/\\');
        $this->loadConfiguration($config);
        $this->initializeStorage();
        $this->initializeBot();
        $this->loadRoutesAndControllers();
    }

    /**
     * Fluent factory builder to create and configure a bot application.
     */
    #[\NoDiscard]
    public static function create(string $basePath, array $config = []): static
    {
        return new static($basePath, $config);
    }

    /**
     * Alias for create().
     */
    #[\NoDiscard]
    public static function boot(string $basePath, array $config = []): static
    {
        return static::create($basePath, $config);
    }

    /**
     * Reloads configuration from config.php and rebuilds client instance.
     */
    public function reloadConfig(array $overrides = []): static
    {
        $this->loadConfiguration($overrides);
        $this->initializeStorage();
        $this->initializeBot();
        $this->loadRoutesAndControllers();
        return $this;
    }

    /**
     * Loads config.php and .env if present and merges with overrides.
     */
    private function loadConfiguration(array $overrides): void
    {
        Env::load($this->basePath . '/.env');

        $loaded = [];
        $configFile = $this->basePath . '/config.php';

        if (file_exists($configFile)) {
            $result = require $configFile;
            if (is_array($result)) {
                $loaded = $result;
            }
        }

        // Fill from environment variables as defaults if available
        $envDefaults = [];
        $token = Env::get('TELEGRAM_BOT_TOKEN', Env::get('BOT_TOKEN'));
        if ($token !== null) {
            $envDefaults['token'] = (string)$token;
        }

        $secret = Env::get('TELEGRAM_SECRET_TOKEN', Env::get('BOT_SECRET'));
        if ($secret !== null) {
            $envDefaults['secret_token'] = (string)$secret;
        }

        $webhookUrl = Env::get('TELEGRAM_WEBHOOK_URL', Env::get('WEBHOOK_URL'));
        if ($webhookUrl !== null) {
            $envDefaults['webhook_url'] = (string)$webhookUrl;
        }

        $this->config = array_replace_recursive($envDefaults, $loaded, $overrides);

        // Resolve storage directories
        $this->storagePath = $this->config['storage_path'] ?? ($this->basePath . '/storage');
        $this->flowStoragePath = $this->config['flow_storage'] ?? ($this->storagePath . '/flow');
    }

    /**
     * Ensures storage directories exist and are protected against direct web access.
     */
    private function initializeStorage(): void
    {
        if (!is_dir($this->storagePath)) {
            @mkdir($this->storagePath, 0775, true);
        }

        if (!is_dir($this->flowStoragePath)) {
            @mkdir($this->flowStoragePath, 0775, true);
        }

        // Write Apache .htaccess to deny direct web access to state sessions
        $htaccess = $this->storagePath . '/.htaccess';
        if (!file_exists($htaccess)) {
            @file_put_contents($htaccess, "Deny from all\n");
        }

        // Write index.php fallback for Nginx/IIS
        $indexPhp = $this->storagePath . '/index.php';
        if (!file_exists($indexPhp)) {
            @file_put_contents($indexPhp, "<?php\nhttp_response_code(403);\nexit('Access denied');\n");
        }
    }

    /**
     * Initializes the Telegram client with configuration options and AutoMode.
     */
    private function initializeBot(): void
    {
        $token = (string)($this->config['token'] ?? $this->config['bot_token'] ?? '');

        $builder = Config::builder($token)
            ->withApiServer($this->config['api_server'] ?? 'https://api.telegram.org')
            ->withTimeout((float)($this->config['timeout'] ?? 30.0))
            ->withConnectTimeout((float)($this->config['connect_timeout'] ?? 10.0))
            ->withRetryCount((int)($this->config['retry_count'] ?? 3));

        if (!empty($this->config['proxy'])) {
            $builder->withProxy((string)$this->config['proxy']);
        }

        // Configure default running mode
        $secretToken = $this->config['secret_token'] ?? null;
        $autoDelete = (bool)($this->config['auto_delete_webhook'] ?? true);
        $dropPending = (bool)($this->config['drop_pending_updates'] ?? false);

        $webhookMode = new WebhookMode(secretToken: $secretToken);
        $pollingMode = new PollingMode();
        $autoMode = new AutoMode(
            pollingMode: $pollingMode,
            webhookMode: $webhookMode,
            autoDeleteWebhook: $autoDelete,
            dropPendingUpdatesOnDelete: $dropPending
        );

        $mode = strtolower((string)($this->config['mode'] ?? 'auto'));
        $runningMode = match ($mode) {
            'webhook' => $webhookMode,
            'polling' => $pollingMode,
            default => $autoMode,
        };

        $builder = $builder->withRunningMode($runningMode);

        $this->bot = new Telegram($builder->build());

        // Attach default FileStateStore pointing to storage/flow
        $this->bot->setFlowStore(new FileStateStore($this->flowStoragePath));
    }

    /**
     * Automatically loads routes.php and registers discovered/configured controllers.
     */
    private function loadRoutesAndControllers(): void
    {
        $routesPath = $this->config['routes'] ?? ($this->basePath . '/routes.php');
        if (file_exists($routesPath)) {
            $callback = (function (App $app, Telegram $bot) use ($routesPath) {
                return require $routesPath;
            })($this, $this->bot);

            if (is_callable($callback)) {
                $callback($this, $this->bot);
            }
        }

        // Auto-discover controllers in controllers/ directory if it exists
        ControllerDiscovery::discover($this->basePath . '/controllers', $this->bot);

        if (!empty($this->config['controllers'])) {
            foreach ((array)$this->config['controllers'] as $controller) {
                if (is_string($controller)) {
                    if (is_dir($controller)) {
                        ControllerDiscovery::discover($controller, $this->bot);
                    } elseif (class_exists($controller)) {
                        $this->bot->registerController($controller);
                    }
                }
            }
        }
    }

    /**
     * Runs system diagnostics and self-test checks.
     *
     * @return list<array{title: string, status: 'ok'|'warning'|'error', message: string}>
     */
    public function doctor(): array
    {
        return Doctor::diagnose($this);
    }

    /**
     * Executes the application based on incoming request environment.
     *
     * - In CLI: Starts Polling Mode or executes CLI actions (webhook:set, etc.).
     * - In HTTP GET: Renders the Setup Wizard or Royal Management Dashboard.
     * - In HTTP POST: Handles Webhook updates or Setup submissions.
     */
    public function run(mixed ...$handlers): mixed
    {
        // 1. Terminal / CLI execution
        if ($this->isCliEnvironment()) {
            return (new CliHandler($this))->handle($handlers);
        }

        $method = strtoupper((string)($_SERVER['REQUEST_METHOD'] ?? 'GET'));

        // 2. HTTP GET request (Browser - Web Dashboard or Setup)
        if ($method === 'GET') {
            if ($this->isSetupEnabled()) {
                (new WebDashboard($this))->handle();
                return null;
            }

            http_response_code(200);
            header('Content-Type: text/plain; charset=UTF-8');
            echo "Tueen Telegram Bot is active.\n";
            return null;
        }

        // 3. HTTP POST request: Check if it's a Dashboard action
        if (isset($_REQUEST['action']) && $this->isSetupEnabled()) {
            (new WebDashboard($this))->handle();
            return null;
        }

        // 4. HTTP POST request: Telegram Webhook Update
        return $this->bot->run(...$handlers);
    }

    /**
     * Checks if current runtime is a CLI terminal environment.
     */
    #[\NoDiscard]
    public function isCliEnvironment(): bool
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            return false;
        }

        $sapi = PHP_SAPI;
        if ($sapi === 'cli' || $sapi === 'phpdbg') {
            return true;
        }

        return false;
    }

    /**
     * Checks if the Web Setup and Management Dashboard is enabled.
     */
    #[\NoDiscard]
    public function isSetupEnabled(): bool
    {
        return (bool)($this->config['setup']['enabled'] ?? true);
    }

    /**
     * Detects public HTTPS URL pointing to the active bot script.
     */
    #[\NoDiscard]
    public function detectWebhookUrl(): string
    {
        if (!empty($this->config['webhook_url'])) {
            return (string)$this->config['webhook_url'];
        }

        if ($this->isCliEnvironment()) {
            return 'https://example.com/index.php';
        }

        $isHttps = (
            (isset($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) === 'on') ||
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower((string)$_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') ||
            (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower((string)$_SERVER['HTTP_X_FORWARDED_SSL']) === 'on') ||
            ((int)($_SERVER['SERVER_PORT'] ?? 80) === 443)
        );

        $scheme = $isHttps ? 'https' : 'http';
        $host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';
        $script = $_SERVER['SCRIPT_NAME'] ?? $_SERVER['PHP_SELF'] ?? '/index.php';

        return "{$scheme}://{$host}{$script}";
    }

    /**
     * Returns underlying Telegram client instance.
     */
    public function bot(): Telegram
    {
        return $this->bot;
    }

    /**
     * Alias for bot().
     */
    public function getBot(): Telegram
    {
        return $this->bot;
    }

    /**
     * Returns the update Router instance.
     */
    public function router(): Router
    {
        return $this->bot->router();
    }

    /**
     * Returns the conversation FlowManager instance.
     */
    public function flowManager(): FlowManager
    {
        return $this->bot->flowManager();
    }

    public function onCommand(string $command, mixed $handler): static
    {
        $this->bot->onCommand($command, $handler);
        return $this;
    }

    public function onCallbackQuery(?string $pattern, mixed $handler): static
    {
        $this->bot->onCallbackQuery($pattern, $handler);
        return $this;
    }

    public function onMessage(?string $pattern, mixed $handler): static
    {
        $this->bot->onMessage($pattern, $handler);
        return $this;
    }

    public function onInlineQuery(?string $pattern, mixed $handler): static
    {
        $this->bot->onInlineQuery($pattern, $handler);
        return $this;
    }

    public function on(string|\Tueen\Telegram\Enums\UpdateType $type, mixed $handler): static
    {
        $this->bot->on($type, $handler);
        return $this;
    }

    public function onFallback(mixed $handler): static
    {
        $this->bot->onFallback($handler);
        return $this;
    }

    public function registerController(string|object $controller): static
    {
        $this->bot->registerController($controller);
        return $this;
    }

    public function handle(mixed ...$handlers): static
    {
        $this->bot->handle(...$handlers);
        return $this;
    }

    public function use(callable $middleware): static
    {
        $this->bot->use($middleware);
        return $this;
    }

    public function middleware(callable $middleware): static
    {
        $this->bot->middleware($middleware);
        return $this;
    }

    public function pipe(MiddlewareInterface|Closure $middleware): static
    {
        $this->bot->pipe($middleware);
        return $this;
    }

    /**
     * Dynamically proxies method calls to the underlying Telegram client.
     */
    public function __call(string $name, array $arguments): mixed
    {
        return $this->bot->$name(...$arguments);
    }
}
