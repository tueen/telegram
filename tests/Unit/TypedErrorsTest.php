<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Tueen\Telegram\Client\HttpClientInterface;
use Tueen\Telegram\Client\Response;
use Tueen\Telegram\Enums\ErrorHandlingMode;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\BadRequestException;
use Tueen\Telegram\Exceptions\BotBlockedException;
use Tueen\Telegram\Exceptions\CantParseEntitiesException;
use Tueen\Telegram\Exceptions\ChatNotFoundException;
use Tueen\Telegram\Exceptions\ConflictException;
use Tueen\Telegram\Exceptions\ErrorMatcher;
use Tueen\Telegram\Exceptions\FileTooLargeException;
use Tueen\Telegram\Exceptions\ForbiddenException;
use Tueen\Telegram\Exceptions\MessageTooLongException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Exceptions\UnauthorizedException;
use Tueen\Telegram\Methods\SendMessage;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Error;

class TypedErrorsTest extends TestCase
{
    public function testErrorMatcherResolvesSpecificCodes(): void
    {
        $this->assertSame(
            TelegramErrorCode::ChatNotFound,
            ErrorMatcher::matchCode(400, 'Bad Request: chat not found')
        );

        $this->assertSame(
            TelegramErrorCode::BotBlocked,
            ErrorMatcher::matchCode(403, 'Forbidden: bot was blocked by the user')
        );

        $this->assertSame(
            TelegramErrorCode::FloodWait,
            ErrorMatcher::matchCode(429, 'Too Many Requests: retry after 25')
        );

        $this->assertSame(
            TelegramErrorCode::CantParseEntities,
            ErrorMatcher::matchCode(400, "Bad Request: can't parse entities: Character '.' is reserved")
        );

        $this->assertSame(
            TelegramErrorCode::MessageTooLong,
            ErrorMatcher::matchCode(400, 'Bad Request: message is too long')
        );

        $this->assertSame(
            TelegramErrorCode::Unauthorized,
            ErrorMatcher::matchCode(401, 'Unauthorized: invalid token specified')
        );

        $this->assertSame(
            TelegramErrorCode::FileTooLarge,
            ErrorMatcher::matchCode(413, 'Request Entity Too Large')
        );

        // Unknown future error fallback
        $this->assertSame(
            TelegramErrorCode::Unknown,
            ErrorMatcher::matchCode(400, 'Bad Request: some brand new future telegram error description')
        );
    }

    public function testErrorMatcherCreatesSpecificExceptions(): void
    {
        $ex = ErrorMatcher::createException('Bad Request: chat not found', 400);
        $this->assertInstanceOf(ChatNotFoundException::class, $ex);
        $this->assertInstanceOf(BadRequestException::class, $ex);
        $this->assertInstanceOf(ApiException::class, $ex);
        $this->assertSame(TelegramErrorCode::ChatNotFound, $ex->reason);
        $this->assertTrue($ex->is(TelegramErrorCode::ChatNotFound));
        $this->assertTrue($ex->isChatNotFound());
        $this->assertFalse($ex->isBotBlocked());

        $blockedEx = ErrorMatcher::createException('Forbidden: bot was blocked by the user', 403);
        $this->assertInstanceOf(BotBlockedException::class, $blockedEx);
        $this->assertInstanceOf(ForbiddenException::class, $blockedEx);
        $this->assertSame(TelegramErrorCode::BotBlocked, $blockedEx->reason);
        $this->assertTrue($blockedEx->isBotBlocked());

        $rateEx = ErrorMatcher::createException('Too Many Requests: retry after 30', 429, ['retry_after' => 30]);
        $this->assertInstanceOf(RateLimitException::class, $rateEx);
        $this->assertSame(30, $rateEx->getRetryAfter());
        $this->assertSame(TelegramErrorCode::FloodWait, $rateEx->reason);
        $this->assertTrue($rateEx->isRateLimit());
    }

    public function testClientThrowsSpecificExceptionInDefaultMode(): void
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

        $telegram = new Telegram(
            Telegram::create('TEST_TOKEN')
                ->withHttpClient($mockHttp)
                ->build()
        );

        $caught = false;
        try {
            $telegram->sendMessage(chatId: 12345, text: 'Hello');
        } catch (ChatNotFoundException $e) {
            $caught = true;
            $this->assertTrue($e->isChatNotFound());
            $this->assertSame(400, $e->errorCode);
            $this->assertSame(TelegramErrorCode::ChatNotFound, $e->reason);
        }

        $this->assertTrue($caught, 'ChatNotFoundException was not caught.');
    }

    public function testErrorObjectHasReasonAndHelpers(): void
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

        $telegram = new Telegram(
            Telegram::create('TEST_TOKEN')
                ->withHttpClient($mockHttp)
                ->withErrorObjectMode()
                ->build()
        );

        /** @var Error $result */
        $result = $telegram->sendMessage(chatId: 99999, text: 'Hello');

        $this->assertInstanceOf(Error::class, $result);
        $this->assertFalse($result->ok());
        $this->assertSame(TelegramErrorCode::BotBlocked, $result->reason);
        $this->assertTrue($result->is(TelegramErrorCode::BotBlocked));
        $this->assertTrue($result->isBotBlocked());
        $this->assertFalse($result->isChatNotFound());

        // Test toException conversion
        $ex = $result->toException();
        $this->assertInstanceOf(BotBlockedException::class, $ex);
        $this->assertSame('Forbidden: bot was blocked by the user', $ex->getMessage());
    }

    public function testMethodDeclaresExpectedErrorsViaAttribute(): void
    {
        $method = new SendMessage(chatId: 123, text: 'Test');
        $expectedErrors = $method->getExpectedErrors();

        $this->assertNotEmpty($expectedErrors);
        $this->assertContains(TelegramErrorCode::ChatNotFound, $expectedErrors);
        $this->assertContains(TelegramErrorCode::BotBlocked, $expectedErrors);
        $this->assertContains(TelegramErrorCode::MessageTooLong, $expectedErrors);
        $this->assertContains(TelegramErrorCode::FloodWait, $expectedErrors);
    }

    public function testTelegramErrorCodeEnumMetadata(): void
    {
        $this->assertSame(400, TelegramErrorCode::ChatNotFound->httpStatus());
        $this->assertSame(ChatNotFoundException::class, TelegramErrorCode::ChatNotFound->exceptionClass());

        $this->assertSame(403, TelegramErrorCode::BotBlocked->httpStatus());
        $this->assertSame(BotBlockedException::class, TelegramErrorCode::BotBlocked->exceptionClass());

        $this->assertSame(429, TelegramErrorCode::FloodWait->httpStatus());
        $this->assertSame(RateLimitException::class, TelegramErrorCode::FloodWait->exceptionClass());

        $this->assertSame(0, TelegramErrorCode::Unknown->httpStatus());
        $this->assertSame(ApiException::class, TelegramErrorCode::Unknown->exceptionClass());
    }

    public function testUnknownErrorFallbackGracefullyPreservesDetails(): void
    {
        $apiPayload = [
            'ok' => false,
            'error_code' => 499,
            'description' => 'Some completely unexpected future Telegram error code',
        ];

        $ex = ApiException::fromResponse($apiPayload);
        $this->assertInstanceOf(ApiException::class, $ex);
        $this->assertSame(TelegramErrorCode::Unknown, $ex->reason);
        $this->assertSame(499, $ex->errorCode);
        $this->assertSame('Some completely unexpected future Telegram error code', $ex->getMessage());

        $errorObj = Error::fromResponse($apiPayload);
        $this->assertSame(TelegramErrorCode::Unknown, $errorObj->reason);
        $this->assertSame(499, $errorObj->errorCode);
    }
}
