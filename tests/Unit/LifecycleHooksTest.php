<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Throwable;
use Tueen\Telegram\Client\HttpClientInterface;
use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Client\Response;
use Tueen\Telegram\Config;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Error;
use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Types\User;

class LifecycleHooksTest extends TestCase
{
    public function testLifecycleHooksOnSuccessfulRequest(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);
        $mockHttp->expects($this->once())
            ->method('send')
            ->willReturn(new Response(
                statusCode: 200,
                data: [
                    'ok' => true,
                    'result' => [
                        'id' => 987654,
                        'is_bot' => true,
                        'first_name' => 'TeeBot',
                    ],
                ]
            ));

        $bot = new Telegram(
            Telegram::create('MOCK_TOKEN')
                ->withHttpClient($mockHttp)
                ->build()
        );

        $executionOrder = [];
        $capturedBefore = null;
        $capturedAfter = null;
        $capturedResponse = null;

        $bot->onBeforeRequest(function (Request $req, Config $cfg) use (&$executionOrder, &$capturedBefore) {
            $executionOrder[] = 'before';
            $capturedBefore = [$req, $cfg];
        });

        $bot->onAfterRequest(function (Response $res, Request $req) use (&$executionOrder, &$capturedAfter) {
            $executionOrder[] = 'after';
            $capturedAfter = [$res, $req];
        });

        $bot->onError(function (Throwable $e, Request $req) use (&$executionOrder) {
            $executionOrder[] = 'error';
        });

        $bot->onResponse(function (Type $result, Request $req) use (&$executionOrder, &$capturedResponse) {
            $executionOrder[] = 'response';
            $capturedResponse = [$result, $req];
        });

        /** @var User $botUser */
        $botUser = $bot->getMe();

        $this->assertSame(['before', 'after', 'response'], $executionOrder);
        $this->assertInstanceOf(User::class, $botUser);
        $this->assertSame(987654, $botUser->id);

        $this->assertInstanceOf(Request::class, $capturedBefore[0]);
        $this->assertSame('getMe', $capturedBefore[0]->endpoint);
        $this->assertInstanceOf(Config::class, $capturedBefore[1]);

        $this->assertInstanceOf(Response::class, $capturedAfter[0]);
        $this->assertTrue($capturedAfter[0]->isOk());

        $this->assertInstanceOf(User::class, $capturedResponse[0]);
        $this->assertSame($botUser, $capturedResponse[0]);
    }

    public function testLifecycleHooksOnException(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);
        $mockHttp->expects($this->once())
            ->method('send')
            ->willReturn(new Response(
                statusCode: 403,
                data: [
                    'ok' => false,
                    'error_code' => 403,
                    'description' => 'Forbidden: bot was blocked by the user',
                ]
            ));

        $bot = new Telegram(
            Telegram::create('MOCK_TOKEN')
                ->withHttpClient($mockHttp)
                ->build()
        );

        $executionOrder = [];
        $capturedError = null;

        $bot->onBeforeRequest(function () use (&$executionOrder) {
            $executionOrder[] = 'before';
        });

        $bot->onAfterRequest(function () use (&$executionOrder) {
            $executionOrder[] = 'after';
        });

        $bot->onError(function (Throwable $e, Request $req) use (&$executionOrder, &$capturedError) {
            $executionOrder[] = 'error';
            $capturedError = [$e, $req];
        });

        $bot->onResponse(function () use (&$executionOrder) {
            $executionOrder[] = 'response';
        });

        try {
            $bot->sendMessage(chatId: 12345, text: 'Hi');
            $this->fail('Expected ApiException to be thrown');
        } catch (ApiException $e) {
            $this->assertSame('Forbidden: bot was blocked by the user', $e->getMessage());
        }

        $this->assertSame(['before', 'after', 'error'], $executionOrder);
        $this->assertInstanceOf(ApiException::class, $capturedError[0]);
        $this->assertSame(403, $capturedError[0]->errorCode);
    }

    public function testLifecycleHooksInErrorObjectMode(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);
        $mockHttp->expects($this->once())
            ->method('send')
            ->willReturn(new Response(
                statusCode: 400,
                data: [
                    'ok' => false,
                    'error_code' => 400,
                    'description' => 'Bad Request: message is too long',
                ]
            ));

        $bot = new Telegram(
            Telegram::create('MOCK_TOKEN')
                ->withHttpClient($mockHttp)
                ->withErrorObjectMode()
                ->build()
        );

        $executionOrder = [];
        $capturedResponse = null;

        $bot->onBeforeRequest(function () use (&$executionOrder) {
            $executionOrder[] = 'before';
        });

        $bot->onAfterRequest(function () use (&$executionOrder) {
            $executionOrder[] = 'after';
        });

        $bot->onError(function () use (&$executionOrder) {
            $executionOrder[] = 'error';
        });

        $bot->onResponse(function (Type $result) use (&$executionOrder, &$capturedResponse) {
            $executionOrder[] = 'response';
            $capturedResponse = $result;
        });

        $result = $bot->sendMessage(chatId: 12345, text: 'Very long text');

        // In ErrorObject mode: before -> after -> error -> response (with Error object)
        $this->assertSame(['before', 'after', 'error', 'response'], $executionOrder);
        $this->assertInstanceOf(Error::class, $result);
        $this->assertFalse($result->ok());
        $this->assertSame($result, $capturedResponse);
    }
}
