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

        $this->assertSame('sendMessage', $send->endpoint);
        $this->assertSame('POST', $send->httpMethod);
        $this->assertFalse($send->requiresMultipart);

        $params = $send->parameters;
        $this->assertSame(123456789, $params['chat_id']);
        $this->assertSame('Hello Tueen!', $params['text']);
        $this->assertSame('HTML', $params['parse_mode']);
        $this->assertTrue($params['disable_notification']);
    }

    public function testSendPhotoMultipartWithInputFile(): void
    {
        $file = InputFile::fromString('fake-image-bytes', 'test.jpg', 'image/jpeg');

        $sendPhoto = new SendPhoto(
            chatId: '@mychannel',
            photo: $file,
            caption: 'Queen photo'
        );

        $this->assertSame('sendPhoto', $sendPhoto->endpoint);
        $this->assertTrue($sendPhoto->requiresMultipart);

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

        $params = $method->parameters;
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

    public function testSendMediaGroupExtractsNestedInputFiles(): void
    {
        $file1 = InputFile::fromString('bytes1', 'photo1.jpg');
        $file2 = InputFile::fromString('bytes2', 'photo2.jpg');

        $sendMediaGroup = new \Tueen\Telegram\Methods\SendMediaGroup(
            chatId: 123456,
            media: [
                ['type' => 'photo', 'media' => $file1, 'caption' => 'Photo 1'],
                ['type' => 'photo', 'media' => $file2, 'caption' => 'Photo 2'],
                ['type' => 'photo', 'media' => 'https://example.com/remote.jpg'],
            ]
        );

        $this->assertTrue($sendMediaGroup->requiresMultipart);
        [$params, $files] = $sendMediaGroup->buildRequestData();

        $this->assertCount(2, $files);
        $this->assertArrayHasKey('attach_file_0', $files);
        $this->assertArrayHasKey('attach_file_1', $files);
        $this->assertSame($file1, $files['attach_file_0']);
        $this->assertSame($file2, $files['attach_file_1']);

        // Check media parameter contains attach://
        $mediaDecoded = is_string($params['media']) ? json_decode($params['media'], true) : $params['media'];
        $this->assertSame('attach://attach_file_0', $mediaDecoded[0]['media']);
        $this->assertSame('attach://attach_file_1', $mediaDecoded[1]['media']);
        $this->assertSame('https://example.com/remote.jpg', $mediaDecoded[2]['media']);
    }
}
