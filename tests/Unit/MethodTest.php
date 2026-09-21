<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Enums\ParseMode;
use Tueen\Telegram\Methods\SendMessage;
use Tueen\Telegram\Methods\SendPhoto;
use Tueen\Telegram\Types\Custom\InputFile;

class MethodTest extends TestCase
{
    public function testSendMessageParameters(): void
    {
        $send = new SendMessage(
            chatId: 123456789,
            text: 'Hello Tueen!',
            parseMode: ParseMode::HTML->value,
            disableNotification: true
        );

        $this->assertSame('sendMessage', $send->getEndpoint());
        $this->assertSame('POST', $send->getHttpMethod());
        $this->assertFalse($send->requiresMultipart());

        $params = $send->getParameters();
        $this->assertSame(123456789, $params['chat_id']);
        $this->assertSame('Hello Tueen!', $params['text']);
        $this->assertSame('HTML', $params['parse_mode']);
        $this->assertSame('true', $params['disable_notification']);
    }

    public function testSendPhotoMultipartWithInputFile(): void
    {
        $file = InputFile::fromString('fake-image-bytes', 'test.jpg', 'image/jpeg');

        $sendPhoto = new SendPhoto(
            chatId: '@mychannel',
            photo: $file,
            caption: 'Queen photo'
        );

        $this->assertSame('sendPhoto', $sendPhoto->getEndpoint());
        $this->assertTrue($sendPhoto->requiresMultipart());

        [$params, $files] = $sendPhoto->buildRequestData();

        $this->assertSame('@mychannel', $params['chat_id']);
        $this->assertSame('Queen photo', $params['caption']);
        $this->assertArrayHasKey('photo', $files);
        $this->assertSame($file, $files['photo']);
    }

    public function testMethodInstantiation(): void
    {
        $method = new SendMessage(
            chatId: 987654,
            text: 'Testing make method'
        );

        $this->assertInstanceOf(SendMessage::class, $method);
        $this->assertSame(987654, $method->chatId);
        $this->assertSame('Testing make method', $method->text);
    }

    public function testExtraVariadicNamedParameters(): void
    {
        // 1. Instantiation with custom extra named arguments
        $method = new SendMessage(
            chatId: 112233,
            text: 'Hello forward compatible',
            futureTelegramParam: 'future_value',
            another_snake_param: 42
        );

        $params = $method->getParameters();
        $this->assertSame('future_value', $params['future_telegram_param']);
        $this->assertSame(42, $params['another_snake_param']);
    }

    public function testPositionalExtraParametersThrowException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Extra parameters must be named arguments');

        $method = new SendMessage(112233, 'Hello');
        // Manually simulate positional extra argument
        $method->handleExtraParameters([0 => 'invalid_positional']);
    }
}
