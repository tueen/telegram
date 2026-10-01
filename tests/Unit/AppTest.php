<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\App;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

class AppTest extends TestCase
{
    private string $tempDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tempDir = sys_get_temp_dir() . '/tueen_app_test_' . bin2hex(random_bytes(6));
        @mkdir($this->tempDir, 0777, true);
        $this->tempDir = realpath($this->tempDir) ?: $this->tempDir;
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->tempDir);

        unset(
            $_ENV['TELEGRAM_BOT_TOKEN'], $_ENV['BOT_TOKEN'],
            $_ENV['TELEGRAM_SECRET_TOKEN'], $_ENV['BOT_SECRET'],
            $_ENV['TELEGRAM_WEBHOOK_URL'], $_ENV['WEBHOOK_URL'],
            $_ENV['IS_DEBUG'], $_ENV['COUNT'], $_ENV['EMPTY_VAL'], $_ENV['NULL_VAL'],
            $_SERVER['TELEGRAM_BOT_TOKEN'], $_SERVER['BOT_TOKEN'],
            $_SERVER['TELEGRAM_SECRET_TOKEN'], $_SERVER['BOT_SECRET'],
            $_SERVER['TELEGRAM_WEBHOOK_URL'], $_SERVER['WEBHOOK_URL'],
            $_SERVER['IS_DEBUG'], $_SERVER['COUNT'], $_SERVER['EMPTY_VAL'], $_SERVER['NULL_VAL']
        );
        putenv('TELEGRAM_BOT_TOKEN');
        putenv('BOT_TOKEN');
        putenv('TELEGRAM_SECRET_TOKEN');
        putenv('BOT_SECRET');
        putenv('TELEGRAM_WEBHOOK_URL');
        putenv('WEBHOOK_URL');

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

    public function testAppInstantiationAndConfigLoading(): void
    {
        $configContent = <<<'PHP'
<?php
return [
    'token' => '123456:TEST_TOKEN_APP',
    'secret_token' => 'my-secret-token',
    'webhook_url' => 'https://bot.example.com/index.php',
];
PHP;
        file_put_contents($this->tempDir . '/config.php', $configContent);

        $app = App::create($this->tempDir);

        $this->assertSame($this->tempDir, $app->basePath);
        $this->assertSame('123456:TEST_TOKEN_APP', $app->config['token']);
        $this->assertSame('my-secret-token', $app->config['secret_token']);
        $this->assertSame('https://bot.example.com/index.php', $app->config['webhook_url']);
        $this->assertInstanceOf(Telegram::class, $app->bot);
        $this->assertSame('123456:TEST_TOKEN_APP', $app->bot->config->botToken);
    }

    public function testDefaultStorageAndFlowConfiguration(): void
    {
        $app = App::create($this->tempDir, ['token' => 'TEST_TOKEN']);

        $expectedStorage = $this->tempDir . '/storage';
        $expectedFlow = $this->tempDir . '/storage/flow';

        $this->assertSame(str_replace('\\', '/', $expectedStorage), str_replace('\\', '/', $app->storagePath));
        $this->assertSame(str_replace('\\', '/', $expectedFlow), str_replace('\\', '/', $app->flowStoragePath));

        $this->assertDirectoryExists($expectedStorage);
        $this->assertDirectoryExists($expectedFlow);

        // Verify security files exist
        $this->assertFileExists($expectedStorage . '/.htaccess');
        $this->assertFileExists($expectedStorage . '/index.php');
        $this->assertStringContainsString('Deny from all', (string)file_get_contents($expectedStorage . '/.htaccess'));

        // Verify flow store was set on FlowManager
        $flowManager = $app->flowManager;
        $this->assertInstanceOf(\Tueen\Telegram\Flow\FlowManager::class, $flowManager);
    }

    public function testAutoLoadsRoutesFile(): void
    {
        $routesContent = <<<'PHP'
<?php
/** @var Tueen\Telegram\App $app */
$app->onCommand('start', function ($update, $bot) {
    return 'START_COMMAND_CALLED';
});

return function (\Tueen\Telegram\App $app) {
    $app->onCommand('help', function ($update, $bot) {
        return 'HELP_COMMAND_CALLED';
    });
};
PHP;
        file_put_contents($this->tempDir . '/routes.php', $routesContent);

        $app = App::create($this->tempDir, ['token' => 'TEST_TOKEN']);
        $router = $app->router;

        $this->assertTrue($router->hasRoutes());

        // Test route dispatch for /start
        $startUpdate = new Update([
            'update_id' => 1,
            'message' => [
                'message_id' => 10,
                'date' => time(),
                'text' => '/start',
                'chat' => ['id' => 123, 'type' => 'private'],
                'from' => ['id' => 123, 'first_name' => 'Alice', 'is_bot' => false],
            ],
        ]);

        $res = $router->dispatch($startUpdate, $app->bot);
        $this->assertSame('START_COMMAND_CALLED', $res);

        // Test route dispatch for /help
        $helpUpdate = new Update([
            'update_id' => 2,
            'message' => [
                'message_id' => 11,
                'date' => time(),
                'text' => '/help',
                'chat' => ['id' => 123, 'type' => 'private'],
                'from' => ['id' => 123, 'first_name' => 'Alice', 'is_bot' => false],
            ],
        ]);

        $res2 = $router->dispatch($helpUpdate, $app->bot);
        $this->assertSame('HELP_COMMAND_CALLED', $res2);
    }

    public function testDetectWebhookUrl(): void
    {
        $app = App::create($this->tempDir, [
            'token' => 'TEST_TOKEN',
            'webhook_url' => 'https://custom.example.org/hook.php',
        ]);
        $this->assertSame('https://custom.example.org/hook.php', $app->detectWebhookUrl());

        // Test auto-detection with $_SERVER mock
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['HTTP_HOST'] = 'mybot.domain.com';
        $_SERVER['SCRIPT_NAME'] = '/telegram/index.php';

        $appAuto = App::create($this->tempDir, ['token' => 'TEST_TOKEN']);

        $detected = $appAuto->detectWebhookUrl();
        $this->assertSame('https://mybot.domain.com/telegram/index.php', $detected);

        // Clean up $_SERVER
        unset($_SERVER['REQUEST_METHOD'], $_SERVER['HTTPS'], $_SERVER['HTTP_HOST'], $_SERVER['SCRIPT_NAME']);
    }

    public function testProxyMethodsWorkSeamlessly(): void
    {
        $app = App::create($this->tempDir, ['token' => 'TEST_TOKEN']);

        // Verify fluent chaining on $app returns $app instance
        $chained = $app->onCommand('start', fn() => 'start')
            ->onMessage('hello', fn() => 'world')
            ->onCallbackQuery('btn', fn() => 'clicked');

        $this->assertSame($app, $chained);
        $this->assertTrue($app->router->hasRoutes());

        $this->assertInstanceOf(\Tueen\Telegram\Routing\Router::class, $app->router);
        $this->assertInstanceOf(\Tueen\Telegram\Flow\FlowManager::class, $app->flowManager);
        $this->assertInstanceOf(Telegram::class, $app->bot);
    }

    public function testRoutesWithBotOnlyParameter(): void
    {
        $routesPath = $this->tempDir . '/routes.php';
        $routesCode = <<<'PHP'
<?php

use Tueen\Telegram\Telegram;

return function (Telegram $bot): void {
    $bot->onCommand('bot_only', fn() => 'BOT_ONLY_SUCCESS');
};
PHP;
        file_put_contents($routesPath, $routesCode);

        $app = App::create($this->tempDir, ['token' => 'TEST_TOKEN']);
        $this->assertTrue($app->router->hasRoutes());

        $update = new Update([
            'update_id' => 300,
            'message' => [
                'message_id' => 301,
                'date' => time(),
                'text' => '/bot_only',
                'chat' => ['id' => 456, 'type' => 'private'],
                'from' => ['id' => 456, 'first_name' => 'Bob', 'is_bot' => false],
            ],
        ]);

        $res = $app->router->dispatch($update, $app->bot);
        $this->assertSame('BOT_ONLY_SUCCESS', $res);
    }

    public function testRoutesWithBotAndAppParameters(): void
    {
        $routesPath = $this->tempDir . '/routes.php';
        $routesCode = <<<'PHP'
<?php

use Tueen\Telegram\App;
use Tueen\Telegram\Telegram;

return function (Telegram $bot, App $app): void {
    $bot->onCommand('bot_and_app', function ($update, $bot) use ($app) {
        return 'BASE:' . basename($app->basePath);
    });
};
PHP;
        file_put_contents($routesPath, $routesCode);

        $app = App::create($this->tempDir, ['token' => 'TEST_TOKEN']);

        $update = new Update([
            'update_id' => 400,
            'message' => [
                'message_id' => 401,
                'date' => time(),
                'text' => '/bot_and_app',
                'chat' => ['id' => 456, 'type' => 'private'],
                'from' => ['id' => 456, 'first_name' => 'Bob', 'is_bot' => false],
            ],
        ]);

        $res = $app->router->dispatch($update, $app->bot);
        $this->assertSame('BASE:' . basename($this->tempDir), $res);
    }

    public function testCliExecutionHelpCommand(): void
    {
        global $argv, $argc;
        $prevArgv = $argv;
        $prevArgc = $argc;

        $argv = ['index.php', '--help'];
        $argc = 2;

        $app = App::create($this->tempDir, ['token' => 'TEST_TOKEN']);

        ob_start();
        $code = $app->run();
        $output = ob_get_clean();

        $argv = $prevArgv;
        $argc = $prevArgc;

        $this->assertSame(0, $code);
        $this->assertStringContainsString('Tueen Telegram CLI Runner', $output);
        $this->assertStringContainsString('webhook:set', $output);
        $this->assertStringContainsString('webhook:delete', $output);
        $this->assertStringContainsString('doctor', $output);
    }

    public function testEnvLoaderAndAppFallback(): void
    {
        $envContent = <<<ENV
# Comment line
TELEGRAM_BOT_TOKEN="999888:ENV_TOKEN"
TELEGRAM_SECRET_TOKEN='secret-from-env'
TELEGRAM_WEBHOOK_URL=https://env.example.com/index.php
IS_DEBUG=true
COUNT=42
EMPTY_VAL=empty
NULL_VAL=null
ENV;
        file_put_contents($this->tempDir . '/.env', $envContent);

        $app = App::create($this->tempDir);

        $this->assertSame('999888:ENV_TOKEN', $app->config['token']);
        $this->assertSame('secret-from-env', $app->config['secret_token']);
        $this->assertSame('https://env.example.com/index.php', $app->config['webhook_url']);

        $this->assertTrue(\Tueen\Telegram\App\Env::get('IS_DEBUG'));
        $this->assertSame('', \Tueen\Telegram\App\Env::get('EMPTY_VAL'));
        $this->assertNull(\Tueen\Telegram\App\Env::get('NULL_VAL'));
        $this->assertSame('default_val', \Tueen\Telegram\App\Env::get('NON_EXISTENT', 'default_val'));
    }

    public function testDoctorDiagnostics(): void
    {
        $app = App::create($this->tempDir, ['token' => 'TEST_TOKEN']);
        $checks = $app->doctor();

        $this->assertIsArray($checks);
        $this->assertNotEmpty($checks);

        $titles = array_column($checks, 'title');
        $this->assertContains('PHP Version', $titles);
        $this->assertContains('PHP Extensions', $titles);
        $this->assertContains('Flow Storage', $titles);
        $this->assertContains('Webhook URL', $titles);

        // Test CLI print
        ob_start();
        \Tueen\Telegram\App\Doctor::printCli($app);
        $out = ob_get_clean();

        $this->assertStringContainsString('Tueen Telegram System Diagnostics', $out);
        $this->assertStringContainsString('Flow Storage', $out);
    }

    public function testControllerAutoDiscovery(): void
    {
        $controllersDir = $this->tempDir . '/controllers';
        @mkdir($controllersDir, 0777, true);

        $controllerCode = <<<'PHP'
<?php

namespace TestApp\Controllers;

use Tueen\Telegram\Routing\Attributes\OnCommand;
use Tueen\Telegram\Types\Update;
use Tueen\Telegram\Telegram;

class TestSampleController
{
    #[OnCommand('ping_discovered')]
    public function handlePing(Update $update, Telegram $bot): string
    {
        return 'PONG_FROM_CONTROLLER';
    }
}
PHP;
        file_put_contents($controllersDir . '/TestSampleController.php', $controllerCode);

        $app = App::create($this->tempDir, ['token' => 'TEST_TOKEN']);

        // Test route dispatch
        $update = new Update([
            'update_id' => 99,
            'message' => [
                'message_id' => 200,
                'date' => time(),
                'text' => '/ping_discovered',
                'chat' => ['id' => 123, 'type' => 'private'],
                'from' => ['id' => 123, 'first_name' => 'Alice', 'is_bot' => false],
            ],
        ]);

        $res = $app->router->dispatch($update, $app->bot);
        $this->assertSame('PONG_FROM_CONTROLLER', $res);
    }
}
