<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Client\CurlHttpClient;
use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Config;
use Tueen\Telegram\Enums\ErrorHandlingMode;
use Tueen\Telegram\Running\PollingMode;
use Tueen\Telegram\Running\WebhookMode;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Custom\ArrayResult;
use Tueen\Telegram\Types\Custom\InputFile;
use Tueen\Telegram\Types\Error;
use Tueen\Telegram\Types\Update;
use Uri\Rfc3986\Uri;

class Php85FeaturesTest extends TestCase
{
    public function testConfigCloneWithExpressions(): void
    {
        $config = new Config(botToken: 'TOKEN_A', timeout: 20.0);

        // Test withTimeout clone with
        $modifiedTimeout = $config->withTimeout(45.5);
        $this->assertNotSame($config, $modifiedTimeout);
        $this->assertSame('TOKEN_A', $modifiedTimeout->botToken);
        $this->assertSame(45.5, $modifiedTimeout->timeout);
        $this->assertSame(20.0, $config->timeout);

        // Test withToken and withProxy clone with
        $modifiedBoth = $modifiedTimeout
            ->withToken('TOKEN_B')
            ->withProxy('http://127.0.0.1:8080')
            ->withTestEnvironment(true)
            ->withErrorHandlingMode(ErrorHandlingMode::ERROR_OBJECT);

        $this->assertSame('TOKEN_B', $modifiedBoth->botToken);
        $this->assertSame('http://127.0.0.1:8080', $modifiedBoth->proxy);
        $this->assertTrue($modifiedBoth->testEnvironment);
        $this->assertSame(ErrorHandlingMode::ERROR_OBJECT, $modifiedBoth->errorHandlingMode);
        $this->assertSame(45.5, $modifiedBoth->timeout);
    }

    public function testRequestCloneWithExpressions(): void
    {
        $request = new Request(
            endpoint: 'sendMessage',
            parameters: ['chat_id' => 123, 'text' => 'Hi']
        );

        $cloned = $request
            ->withEndpoint('sendPhoto')
            ->withParameter('caption', 'Look at this photo')
            ->withHttpMethod('GET');

        $this->assertNotSame($request, $cloned);
        $this->assertSame('sendPhoto', $cloned->endpoint);
        $this->assertSame('GET', $cloned->httpMethod);
        $this->assertSame(123, $cloned->parameters['chat_id']);
        $this->assertSame('Hi', $cloned->parameters['text']);
        $this->assertSame('Look at this photo', $cloned->parameters['caption']);
    }

    public function testUriExtensionIntegration(): void
    {
        $config = new Config(botToken: '123456:TEST_TOKEN', testEnvironment: true);

        $apiUri = $config->getApiUri();
        $this->assertInstanceOf(Uri::class, $apiUri);
        $this->assertSame('api.telegram.org', $apiUri->getHost());
        $this->assertSame('/bot123456:TEST_TOKEN/test', $apiUri->getPath());
        $this->assertSame('https', $apiUri->getScheme());

        $fileUri = $config->getFileUri();
        $this->assertInstanceOf(Uri::class, $fileUri);
        $this->assertSame('/file/bot123456:TEST_TOKEN/test', $fileUri->getPath());

        // Test WebhookMode URI validation
        $webhook = new WebhookMode();
        $this->assertTrue($webhook->validateWebhookUrl('https://example.com/telegram/webhook'));
        $this->assertFalse($webhook->validateWebhookUrl('http://insecure.example.com/webhook'));
        $this->assertFalse($webhook->validateWebhookUrl('invalid-url'));
    }

    public function testArrayFirstAndArrayLast(): void
    {
        $updates = [
            new Update(['update_id' => 10, 'message' => ['message_id' => 1, 'date' => 1, 'chat' => ['id' => 1, 'type' => 'private']]]),
            new Update(['update_id' => 20, 'message' => ['message_id' => 2, 'date' => 2, 'chat' => ['id' => 1, 'type' => 'private']]]),
            new Update(['update_id' => 30, 'message' => ['message_id' => 3, 'date' => 3, 'chat' => ['id' => 1, 'type' => 'private']]]),
        ];

        // Testing in PollingMode
        $polling = new PollingMode();
        $this->assertSame(10, $polling->getFirstUpdate($updates)?->updateId);
        $this->assertSame(30, $polling->getLastUpdate($updates)?->updateId);
        $this->assertNull($polling->getFirstUpdate([]));
        $this->assertNull($polling->getLastUpdate([]));

        // Testing in ArrayResult
        $arrayResult = new ArrayResult(['alpha', 'beta', 'gamma']);
        $this->assertSame('alpha', $arrayResult->first());
        $this->assertSame('gamma', $arrayResult->last());

        $emptyResult = new ArrayResult([]);
        $this->assertNull($emptyResult->first());
        $this->assertNull($emptyResult->last());
    }

    public function testPipeOperatorCompatibility(): void
    {
        $telegram = new Telegram('TEST_TOKEN');

        $rawJson = json_encode([
            'update_id' => 999,
            'message' => [
                'message_id' => 1,
                'date' => 1700000000,
                'chat' => ['id' => 12345, 'type' => 'private'],
                'text' => '  /start arg1 arg2  ',
            ],
        ]);

        // Modern PHP 8.5 Pipe Operator (|>)
        $processedUpdate = $rawJson
            |> $telegram->parseUpdate(...)
            |> (fn(Update $update): Update => $update);

        $this->assertInstanceOf(Update::class, $processedUpdate);
        $this->assertSame(999, $processedUpdate->updateId);
        $this->assertSame('start', $processedUpdate->message?->getCommand());
        $this->assertSame(['arg1', 'arg2'], $processedUpdate->message?->getArgs());
    }

    public function testCurlHttpClientInitialization(): void
    {
        $client = new CurlHttpClient(usePersistentShare: true);
        $this->assertInstanceOf(CurlHttpClient::class, $client);

        // Builder integration
        $config = Telegram::create('TEST_TOKEN')
            ->withCurlClient(persistent: true)
            ->build();

        $this->assertInstanceOf(CurlHttpClient::class, $config->httpClient);
    }

    public function testTypeUniversalOkAndNoDiscard(): void
    {
        $update = new Update(['update_id' => 1]);
        $this->assertTrue($update->ok());
        $this->assertTrue($update->isOk());

        $error = new Error('Something went wrong', 400);
        $this->assertFalse($error->ok());
        $this->assertFalse($error->isOk());
    }
}
