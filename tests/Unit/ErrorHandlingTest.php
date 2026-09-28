<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Tueen\Telegram\Client\HttpClientInterface;
use Tueen\Telegram\Client\Response;
use Tueen\Telegram\Enums\ErrorHandlingMode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\NetworkException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Types\Error;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Types\User;

class ErrorHandlingTest extends TestCase
{
    public function testUniversalOkMethodOnTypesAndError(): void
    {
        $message = new Message([
            'message_id' => 1,
            'date' => 123456789,
            'chat' => ['id' => 123, 'type' => 'private'],
        ]);
        $this->assertTrue($message->ok());
        $this->assertTrue($message->isOk());

        $boolResult = new BooleanResult(true);
        $this->assertTrue($boolResult->ok());
        $this->assertTrue($boolResult->isOk());

        $boolFalseResult = new BooleanResult(false);
        $this->assertTrue($boolFalseResult->ok());
        $this->assertTrue($boolFalseResult->isOk());

        $genericType = new Type(['foo' => 'bar']);
        $this->assertTrue($genericType->ok());
        $this->assertTrue($genericType->isOk());

        $error = new Error('Something went wrong', 400);
        $this->assertFalse($error->ok());
        $this->assertFalse($error->isOk());
    }

    public function testErrorPropertiesAndFactories(): void
    {
        // fromResponse factory with 429 flood wait
        $apiPayload = [
            'ok' => false,
            'error_code' => 429,
            'description' => 'Too Many Requests: retry after 42',
            'parameters' => [
                'retry_after' => 42,
                'migrate_to_chat_id' => -100123456789,
            ],
        ];

        $error = Error::fromResponse($apiPayload);
        $this->assertFalse($error->ok());
        $this->assertSame(429, $error->errorCode);
        $this->assertSame('Too Many Requests: retry after 42', $error->description);
        $this->assertSame(42, $error->getRetryAfter());
        $this->assertSame(-100123456789, $error->getMigrateToChatId());
        $this->assertSame($apiPayload['parameters'], $error->parameters);
        $this->assertArrayHasKey('error_code', $error);
        $this->assertSame(429, $error['error_code']);

        // fromThrowable factory with positive code (should be negated)
        $exception = new RuntimeException('Connection timed out', 28);
        $fromEx = Error::fromThrowable($exception);
        $this->assertFalse($fromEx->ok());
        $this->assertSame('Connection timed out', $fromEx->description);
        $this->assertSame(-28, $fromEx->errorCode);
        $this->assertSame($exception, $fromEx->exception);

        // fromThrowable factory with 0 code (should default to -1)
        $zeroEx = new RuntimeException('Unknown fatal error', 0);
        $fromZero = Error::fromThrowable($zeroEx);
        $this->assertSame(-1, $fromZero->errorCode);
    }

    public function testDefaultModeThrowsApiException(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);
        $mockHttp->expects($this->once())
            ->method('send')
            ->willReturn(new Response(
                statusCode: 400,
                data: [
                    'ok' => false,
                    'error_code' => 400,
                    'description' => 'Bad Request: chat not found',
                ]
            ));

        $bot = new Telegram(
            Telegram::create('TEST_TOKEN')
                ->withHttpClient($mockHttp)
                ->build()
        );

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Bad Request: chat not found');

        $bot->sendMessage(chatId: 99999, text: 'Hello');
    }

    public function testErrorObjectModeReturnsErrorInstance(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);
        $mockHttp->expects($this->once())
            ->method('send')
            ->willReturn(new Response(
                statusCode: 400,
                data: [
                    'ok' => false,
                    'error_code' => 400,
                    'description' => 'Bad Request: chat not found',
                ]
            ));

        $config = Telegram::create('TEST_TOKEN')
            ->withHttpClient($mockHttp)
            ->withErrorObjectMode()
            ->build();

        $this->assertSame(ErrorHandlingMode::ERROR_OBJECT, $config->errorHandlingMode);

        $bot = new Telegram($config);

        $result = $bot->sendMessage(chatId: 99999, text: 'Hello');

        $this->assertInstanceOf(Error::class, $result);
        $this->assertFalse($result->ok());
        $this->assertFalse($result->isOk());
        $this->assertSame(400, $result->errorCode);
        $this->assertSame('Bad Request: chat not found', $result->description);
        $this->assertInstanceOf(ApiException::class, $result->exception);
    }

    public function testRateLimitExceptionConvertedToErrorInErrorObjectMode(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);
        $mockHttp->expects($this->once())
            ->method('send')
            ->willReturn(new Response(
                statusCode: 429,
                data: [
                    'ok' => false,
                    'error_code' => 429,
                    'description' => 'Too Many Requests: retry after 15',
                    'parameters' => [
                        'retry_after' => 15,
                    ],
                ]
            ));

        $config = Telegram::create('TEST_TOKEN')
            ->withHttpClient($mockHttp)
            ->withRetryCount(1)
            ->withErrorObjectMode()
            ->build();

        $bot = new Telegram($config);

        /** @var Error $result */
        $result = $bot->sendMessage(chatId: 12345, text: 'Hello');

        $this->assertInstanceOf(Error::class, $result);
        $this->assertFalse($result->ok());
        $this->assertSame(429, $result->errorCode);
        $this->assertSame(15, $result->getRetryAfter());
        $this->assertInstanceOf(RateLimitException::class, $result->exception);
    }

    public function testCatchAllErrorsCatchesNetworkExceptions(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);
        $mockHttp->expects($this->once())
            ->method('send')
            ->willThrowException(new NetworkException('cURL error 28: Resolving timed out', 28));

        $config = Telegram::create('TEST_TOKEN')
            ->withHttpClient($mockHttp)
            ->withRetryCount(1)
            ->withCatchAllErrors()
            ->build();

        $bot = new Telegram($config);

        /** @var Error $result */
        $result = $bot->getMe();

        $this->assertInstanceOf(Error::class, $result);
        $this->assertFalse($result->ok());
        $this->assertSame('cURL error 28: Resolving timed out', $result->description);
        $this->assertSame(-28, $result->errorCode);
        $this->assertInstanceOf(NetworkException::class, $result->exception);
    }

    public function testExceptionModeIgnoresThrowableConversion(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);
        $mockHttp->expects($this->once())
            ->method('send')
            ->willThrowException(new NetworkException('Connection reset by peer', 104));

        $config = Telegram::create('TEST_TOKEN')
            ->withHttpClient($mockHttp)
            ->withRetryCount(1)
            ->withExceptionMode()
            ->build();

        $bot = new Telegram($config);

        $this->expectException(NetworkException::class);
        $this->expectExceptionMessage('Connection reset by peer');

        $bot->getMe();
    }
}
