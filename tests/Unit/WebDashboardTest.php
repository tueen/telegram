<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\App;
use Tueen\Telegram\App\WebDashboard;

class WebDashboardTest extends TestCase
{
    private string $tempDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tempDir = sys_get_temp_dir() . '/tueen_dashboard_test_' . bin2hex(random_bytes(6));
        @mkdir($this->tempDir, 0777, true);
        $this->tempDir = realpath($this->tempDir) ?: $this->tempDir;

        // Clean up session and superglobals before each test
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        $_SESSION = [];
        $_POST = [];
        $_GET = [];
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/index.php';

        unset(
            $_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'],
            $_ENV['SETUP_PASSWORD'],
            $_ENV['TELEGRAM_SETUP_PASSWORD'],
            $_ENV['DASHBOARD_PASSWORD'],
            $_ENV['SETUP_ENABLED'],
            $_ENV['TELEGRAM_SETUP_ENABLED']
        );
        putenv('SETUP_PASSWORD');
        putenv('TELEGRAM_SETUP_PASSWORD');
        putenv('DASHBOARD_PASSWORD');
        putenv('SETUP_ENABLED');
        putenv('TELEGRAM_SETUP_ENABLED');
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->tempDir);

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        $_SESSION = [];
        $_POST = [];
        $_GET = [];
        $_REQUEST = [];

        unset(
            $_SERVER['REMOTE_ADDR'],
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['SCRIPT_NAME'],
            $_SERVER['REQUEST_URI'],
            $_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'],
            $_ENV['SETUP_PASSWORD'],
            $_ENV['TELEGRAM_SETUP_PASSWORD'],
            $_ENV['DASHBOARD_PASSWORD'],
            $_ENV['SETUP_ENABLED'],
            $_ENV['TELEGRAM_SETUP_ENABLED']
        );
        putenv('SETUP_PASSWORD');
        putenv('TELEGRAM_SETUP_PASSWORD');
        putenv('DASHBOARD_PASSWORD');
        putenv('SETUP_ENABLED');
        putenv('TELEGRAM_SETUP_ENABLED');

        parent::tearDown();
    }

    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = scandir($dir);
        if ($files === false) {
            return;
        }

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $path = "{$dir}/{$file}";
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                @unlink($path);
            }
        }
        @rmdir($dir);
    }

    public function testIsDashboardRequestForGetWhenSetupEnabled(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $app = App::create($this->tempDir);
        $this->assertTrue($app->isDashboardRequest());
    }

    public function testIsDashboardRequestForGetWhenSetupDisabled(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $app = App::create($this->tempDir, [
            'setup' => ['enabled' => false],
        ]);
        $this->assertFalse($app->isDashboardRequest());
    }

    public function testIsDashboardRequestForPostWithPasswordSubmission(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['dashboard_password'] = 'secret123';

        $app = App::create($this->tempDir, [
            'setup' => ['password' => 'secret123'],
        ]);

        $this->assertTrue($app->isDashboardRequest());
    }

    public function testIsDashboardRequestForPostWithAction(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_REQUEST['action'] = 'login';

        $app = App::create($this->tempDir, [
            'setup' => ['password' => 'secret123'],
        ]);

        $this->assertTrue($app->isDashboardRequest());
    }

    public function testIsDashboardRequestReturnsFalseForTelegramWebhookUpdate(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [];
        $_GET = [];
        $_REQUEST = [];

        $app = App::create($this->tempDir);

        // A Telegram webhook POST has no $_POST fields and no dashboard action
        $this->assertFalse($app->isDashboardRequest());

        // Even if some header exists, secret token header strictly marks it as webhook
        $_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'] = 'super-secret';
        $_POST['dashboard_password'] = 'ignored';
        $this->assertFalse($app->isDashboardRequest());
    }

    public function testPasswordScreenRenderedWhenPasswordConfigured(): void
    {
        $app = App::create($this->tempDir, [
            'setup' => ['password' => 'royal_secret'],
        ]);

        $dashboard = new WebDashboard($app);
        $dashboard->terminate = false;

        ob_start();
        $dashboard->handle();
        $output = ob_get_clean();

        $this->assertStringContainsString('Tueen Dashboard - Authentication', $output);
        $this->assertStringContainsString('action="?action=login"', $output);
        $this->assertStringContainsString('name="dashboard_password"', $output);
        $this->assertStringContainsString('Unlock Dashboard', $output);
        $this->assertEmpty($_SESSION['tueen_dashboard_auth'] ?? false);
    }

    public function testWrongPasswordDisplaysErrorAlert(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['dashboard_password'] = 'wrong_password';
        $_REQUEST['dashboard_password'] = 'wrong_password';

        $app = App::create($this->tempDir, [
            'setup' => ['password' => 'royal_secret'],
        ]);

        $dashboard = new WebDashboard($app);
        $dashboard->terminate = false;

        ob_start();
        $dashboard->handle();
        $output = ob_get_clean();

        $this->assertStringContainsString('alert-danger', $output);
        $this->assertStringContainsString('Invalid dashboard password', $output);
        $this->assertEmpty($_SESSION['tueen_dashboard_auth'] ?? false);
    }

    public function testCorrectPasswordAuthenticatesSession(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['dashboard_password'] = 'royal_secret';
        $_REQUEST['dashboard_password'] = 'royal_secret';

        $app = App::create($this->tempDir, [
            'setup' => ['password' => 'royal_secret'],
        ]);

        $dashboard = new WebDashboard($app);
        $dashboard->terminate = false;

        ob_start();
        $dashboard->handle();
        ob_end_clean();

        // Verify session was authenticated
        $this->assertTrue($_SESSION['tueen_dashboard_auth'] ?? false);
    }

    public function testAuthenticatedSessionBypassesPasswordGate(): void
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }
        $_SESSION['tueen_dashboard_auth'] = true;
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $app = App::create($this->tempDir, [
            'setup' => ['password' => 'royal_secret'],
        ]);

        $dashboard = new WebDashboard($app);
        $dashboard->terminate = false;

        ob_start();
        $dashboard->handle();
        $output = ob_get_clean();

        // Must display dashboard / setup wizard, NOT the password gate
        $this->assertStringNotContainsString('Enter dashboard password to continue', $output);
        $this->assertStringContainsString('First-Time Bot Setup', $output);
        $this->assertStringContainsString('Logout', $output);
    }

    public function testLogoutActionClearsSessionAndShowsNotice(): void
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }
        $_SESSION['tueen_dashboard_auth'] = true;
        $_GET['action'] = 'logout';
        $_REQUEST['action'] = 'logout';

        $app = App::create($this->tempDir, [
            'setup' => ['password' => 'royal_secret'],
        ]);

        $dashboard = new WebDashboard($app);
        $dashboard->terminate = false;

        ob_start();
        $dashboard->handle();
        $output = ob_get_clean();

        $this->assertEmpty($_SESSION['tueen_dashboard_auth'] ?? false);
        $this->assertStringContainsString('You have been logged out successfully', $output);
        $this->assertStringContainsString('action="?action=login"', $output);
    }

    public function testRemoteAccessWithoutPasswordShowsProtectionNotice(): void
    {
        $_SERVER['REMOTE_ADDR'] = '203.0.113.42'; // Remote public IP
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $app = App::create($this->tempDir); // No password configured

        $dashboard = new WebDashboard($app);
        $dashboard->terminate = false;

        ob_start();
        $dashboard->handle();
        $output = ob_get_clean();

        $this->assertStringContainsString('Remote Access Protected', $output);
        $this->assertStringContainsString('SETUP_PASSWORD', $output);
        $this->assertStringNotContainsString('action="?action=login"', $output);
    }

    public function testLocalAccessWithoutPasswordAllowedDirectly(): void
    {
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1'; // Local loopback
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $app = App::create($this->tempDir); // No password configured

        $dashboard = new WebDashboard($app);
        $dashboard->terminate = false;

        ob_start();
        $dashboard->handle();
        $output = ob_get_clean();

        // Local loopback is allowed directly to setup wizard
        $this->assertStringContainsString('First-Time Bot Setup', $output);
        $this->assertStringNotContainsString('Remote Access Protected', $output);
    }

    public function testPasswordLoadedFromEnv(): void
    {
        file_put_contents($this->tempDir . '/.env', "SETUP_PASSWORD=env_secret_1234\n");

        $app = App::create($this->tempDir);

        $this->assertSame('env_secret_1234', $app->config['setup']['password']);
    }

    public function testPasswordScreenWorksViaAppRunDirectly(): void
    {
        // Simulate real browser POST with wrong password
        $app = App::create($this->tempDir, [
            'setup' => ['password' => 'my_master_password'],
        ]);

        $this->assertTrue($app->isDashboardRequest());
    }

    public function testSaveConfigPreservesExistingOptions(): void
    {
        $existingConfig = <<<'PHP'
<?php
return [
    'proxy' => 'socks5://127.0.0.1:9050',
    'api_server' => 'https://custom.api.telegram.org',
    'setup' => [
        'password' => 'existing_secret',
    ],
];
PHP;
        file_put_contents($this->tempDir . '/config.php', $existingConfig);

        $app = App::create($this->tempDir);
        $dashboard = new WebDashboard($app);

        // Access private saveConfig via reflection
        $ref = new \ReflectionClass(WebDashboard::class);
        $saveMethod = $ref->getMethod('saveConfig');
        $saveMethod->invoke($dashboard, '123456789:ABCdefGhIJKlmNoPQRsTUVwxyZ1234', 'my_secret', 'https://bot.example.com/hook');

        $saved = require $this->tempDir . '/config.php';
        $this->assertSame('123456789:ABCdefGhIJKlmNoPQRsTUVwxyZ1234', $saved['token']);
        $this->assertSame('my_secret', $saved['secret_token']);
        $this->assertSame('https://bot.example.com/hook', $saved['webhook_url']);
        $this->assertSame('socks5://127.0.0.1:9050', $saved['proxy']);
        $this->assertSame('https://custom.api.telegram.org', $saved['api_server']);
        $this->assertSame('existing_secret', $saved['setup']['password']);
    }

    public function testProxyAndApiServerLoadedFromEnv(): void
    {
        file_put_contents(
            $this->tempDir . '/.env',
            "TELEGRAM_PROXY=http://127.0.0.1:8080\nTELEGRAM_API_SERVER=https://local.bot-api.internal\n"
        );

        $app = App::create($this->tempDir);

        $this->assertSame('http://127.0.0.1:8080', $app->config['proxy']);
        $this->assertSame('https://local.bot-api.internal', $app->config['api_server']);
    }

    public function testCreateBotClientInheritsAppProxyAndApiServer(): void
    {
        $app = App::create($this->tempDir, [
            'proxy' => 'http://proxy.local:3128',
            'api_server' => 'https://custom.tg.server',
            'timeout' => 45.0,
        ]);

        $dashboard = new WebDashboard($app);
        $ref = new \ReflectionClass(WebDashboard::class);
        $clientMethod = $ref->getMethod('createBotClient');
        /** @var \Tueen\Telegram\Telegram $client */
        $client = $clientMethod->invoke($dashboard, '123456:TEST_TOKEN');

        $this->assertSame('http://proxy.local:3128', $client->config->proxy);
        $this->assertSame('https://custom.tg.server', $client->config->apiServer);
        $this->assertSame(45.0, $client->config->timeout);
    }
}
