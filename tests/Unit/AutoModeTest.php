<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Client\HttpClientInterface;
use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Client\Response;
use Tueen\Telegram\Config;
use Tueen\Telegram\Running\AutoMode;
use Tueen\Telegram\Running\PollingMode;
use Tueen\Telegram\Running\WebhookMode;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

class AutoModeTest extends TestCase
{
    protected function tearDown(): void
    {
        unset($_SERVER['REQUEST_METHOD']);
        unset($_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN']);
        parent::tearDown();
    }

    public function testAutoModeDefaultsAndGettersSetters(): void
    {
        $auto = new AutoMode();

        $this->assertInstanceOf(PollingMode::class, $auto->getPollingMode());
        $this->assertInstanceOf(WebhookMode::class, $auto->getWebhookMode());
        $this->assertFalse($auto->isAutoDeleteWebhook());

        $newPolling = new PollingMode(timeout: 45);
        $newWebhook = new WebhookMode(secretToken: 'secret_abc');

        $auto->setPollingMode($newPolling);
        $auto->setWebhookMode($newWebhook);
        $auto->setAutoDeleteWebhook(true, true);

        $this->assertSame($newPolling, $auto->getPollingMode());
        $this->assertSame($newWebhook, $auto->getWebhookMode());
        $this->assertTrue($auto->isAutoDeleteWebhook());

        $factoryAuto = AutoMode::create(
            pollingMode: $newPolling,
            webhookMode: $newWebhook,
            autoDeleteWebhook: true
        );
        $this->assertSame($newPolling, $factoryAuto->getPollingMode());
        $this->assertSame($newWebhook, $factoryAuto->getWebhookMode());
        $this->assertTrue($factoryAuto->isAutoDeleteWebhook());
    }

    public function testEnvironmentDetection(): void
    {
        $auto = new AutoMode();

        // In standard PHPUnit execution, PHP_SAPI is 'cli'
        $this->assertTrue($auto->isCliEnvironment());

        // Force HTTP
        $auto->forceHttp();
        $this->assertFalse($auto->isCliEnvironment());

        // Force CLI
        $auto->forceCli();
        $this->assertTrue($auto->isCliEnvironment());

        // Clear forced state
        $auto->forceCli(false);

        // HTTP POST simulation (e.g. RoadRunner/Swoole/FrankenPHP workers)
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertFalse($auto->isCliEnvironment());
        unset($_SERVER['REQUEST_METHOD']);

        // Telegram Secret Token Header simulation
        $_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'] = 'test-token';
        $this->assertFalse($auto->isCliEnvironment());
        unset($_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN']);

        // Custom detector
        $auto->setDetector(fn() => false);
        $this->assertFalse($auto->isCliEnvironment());

        $auto->setDetector(fn() => true);
        $this->assertTrue($auto->isCliEnvironment());
    }

    public function testResolvesActiveModeAndTriggersCallback(): void
    {
        $bot = new Telegram('TEST_TOKEN');
        $polling = new PollingMode();
        $webhook = new WebhookMode();
        $auto = new AutoMode($polling, $webhook);

        $resolvedName = null;
        $resolvedInstance = null;
        $auto->onModeResolved(function ($mode, string $name, Telegram $b) use (&$resolvedName, &$resolvedInstance) {
            $resolvedName = $name;
            $resolvedInstance = $mode;
        });

        // 1. In CLI
        $auto->forceCli();
        $activeMode = $auto->resolveActiveMode($bot);
        $this->assertSame($polling, $activeMode);
        $this->assertSame('polling', $resolvedName);
        $this->assertSame($polling, $resolvedInstance);

        // 2. In HTTP
        $auto2 = new AutoMode($polling, $webhook);
        $auto2->forceHttp();
        $auto2->onModeResolved(function ($mode, string $name) use (&$resolvedName, &$resolvedInstance) {
            $resolvedName = $name;
            $resolvedInstance = $mode;
        });

        $activeMode2 = $auto2->resolveActiveMode($bot);
        $this->assertSame($webhook, $activeMode2);
        $this->assertSame('webhook', $resolvedName);
        $this->assertSame($webhook, $resolvedInstance);
    }

    public function testAutoModeExecutionInWebhookMode(): void
    {
        $payload = json_encode([
            'update_id' => 999,
            'message' => [
                'message_id' => 10,
                'date' => 1700000000,
                'chat' => ['id' => 456, 'type' => 'private'],
                'text' => '/start',
            ],
        ]);

        $webhook = new WebhookMode(rawInput: $payload);
        $auto = new AutoMode(webhookMode: $webhook);
        $auto->forceHttp();

        $bot = new Telegram('TEST_TOKEN');
        $bot->setRunningMode($auto);

        $handled = false;
        $result = $bot->run(function (Update $update) use (&$handled) {
            $handled = true;
            $this->assertSame(999, $update->updateId);
            $this->assertSame('/start', $update->message?->text);
        });

        $this->assertTrue($handled);
        $this->assertInstanceOf(Update::class, $result);
        $this->assertSame(999, $result->updateId);
    }

    public function testAutoModeExecutionInPollingMode(): void
    {
        $fakeHttp = new \Tueen\Telegram\Testing\FakeHttpClient([
            'getUpdates' => [
                'ok' => true,
                'result' => [
                    [
                        'update_id' => 1001,
                        'message' => [
                            'message_id' => 20,
                            'date' => 1700000000,
                            'chat' => ['id' => 789, 'type' => 'private'],
                            'text' => 'polling update',
                        ],
                    ],
                ],
            ],
        ]);

        $config = Telegram::create('TEST_TOKEN')
            ->withHttpClient($fakeHttp)
            ->build();

        $bot = new Telegram($config);

        $polling = new PollingMode(timeout: 1, limit: 1);
        $polling->setProcessDispatcher(function (Update $update, Telegram $b, callable $next) use ($polling) {
            $next();
            $polling->stop();
        });

        $auto = new AutoMode(pollingMode: $polling);
        $auto->forceCli();
        $bot->setRunningMode($auto);

        $handled = false;
        $bot->run(function (Update $update) use (&$handled) {
            $handled = true;
            $this->assertSame(1001, $update->updateId);
            $this->assertSame('polling update', $update->message?->text);
        });

        $this->assertTrue($handled);
    }

    public function testAutoModeAutoDeleteWebhookWhenSwitchingToPolling(): void
    {
        $fakeHttp = new \Tueen\Telegram\Testing\FakeHttpClient([
            'deleteWebhook' => ['ok' => true, 'result' => true],
            'getUpdates' => [
                'ok' => true,
                'result' => [
                    ['update_id' => 100],
                ],
            ],
        ]);

        $config = Telegram::create('TEST_TOKEN')
            ->withHttpClient($fakeHttp)
            ->build();

        $bot = new Telegram($config);

        $polling = new PollingMode();
        $polling->setProcessDispatcher(function () use ($polling) {
            $polling->stop();
        });

        $auto = new AutoMode(
            pollingMode: $polling,
            autoDeleteWebhook: true,
            dropPendingUpdatesOnDelete: true
        );
        $auto->forceCli();

        $auto->processUpdate($bot);

        $this->assertTrue($fakeHttp->hasSent('deleteWebhook'));
    }

    public function testConfigBuilderFluentAutoModeAndClientFactory(): void
    {
        $polling = new PollingMode(timeout: 25);
        $webhook = new WebhookMode(secretToken: 'secret_xyz');

        $client = Telegram::create('TEST_TOKEN')
            ->withPollingMode($polling)
            ->withWebhookMode($webhook)
            ->withAutoMode(autoDeleteWebhook: true)
            ->client();

        $runningMode = $client->getRunningMode();
        $this->assertInstanceOf(AutoMode::class, $runningMode);
        $this->assertSame($polling, $runningMode->getPollingMode());
        $this->assertSame($webhook, $runningMode->getWebhookMode());
        $this->assertTrue($runningMode->isAutoDeleteWebhook());
    }

    public function testTelegramUseAutoModeAndAutoRun(): void
    {
        $payload = json_encode([
            'update_id' => 888,
            'message' => [
                'message_id' => 1,
                'date' => 1700000000,
                'chat' => ['id' => 1, 'type' => 'private'],
                'text' => 'autorun test',
            ],
        ]);

        $bot = new Telegram('TEST_TOKEN');
        $webhook = new WebhookMode(rawInput: $payload);

        $bot->useAutoMode(
            webhook: $webhook,
            detector: fn() => false // Force Webhook
        );

        $this->assertInstanceOf(AutoMode::class, $bot->getRunningMode());

        $executed = false;
        $bot->autoRun(function (Update $update) use (&$executed) {
            $executed = true;
            $this->assertSame('autorun test', $update->message?->text);
        });

        $this->assertTrue($executed);
    }

    public function testAutoModeProxiesWebhookMethods(): void
    {
        $payload = json_encode(['update_id' => 777]);
        $webhook = new WebhookMode(rawInput: $payload);
        $auto = new AutoMode(webhookMode: $webhook);
        $bot = new Telegram('TEST_TOKEN');

        $update = $auto->resolveUpdate($bot);
        $this->assertSame(777, $update->updateId);

        // processPsrRequest proxy
        $psrRequest = new ServerRequest(
            'POST',
            '/webhook',
            ['Content-Type' => 'application/json'],
            json_encode(['update_id' => 778])
        );

        $response = $auto->processPsrRequest($psrRequest, $bot);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(778, $bot->update?->updateId);
    }
}
