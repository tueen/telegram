<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Client\HttpClientInterface;
use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Client\Response;
use Tueen\Telegram\Methods\SendDocument;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Custom\InputFile;

class ProgressTest extends TestCase
{
    public function testUploadProgressCallbackInvoked(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);

        $mockHttp->expects($this->once())
            ->method('send')
            ->willReturnCallback(function ($config, Request $req) {
                // Trigger upload progress
                if ($req->uploadProgress !== null) {
                    ($req->uploadProgress)(512, 1024, 50.0);
                    ($req->uploadProgress)(1024, 1024, 100.0);
                }
                return new Response(statusCode: 200, data: ['ok' => true, 'result' => true]);
            });

        $progressEvents = [];
        $callback = function (int $uploaded, int $total, float $pct) use (&$progressEvents) {
            $progressEvents[] = [$uploaded, $total, $pct];
        };

        $config = Telegram::create('TOKEN')
            ->withHttpClient($mockHttp)
            ->withUploadProgress($callback)
            ->build();

        $bot = new Telegram($config);

        $doc = InputFile::fromString('content', 'test.txt');
        $bot->send(new SendDocument(chatId: 12345, document: $doc));

        $this->assertCount(2, $progressEvents);
        $this->assertSame([512, 1024, 50.0], $progressEvents[0]);
        $this->assertSame([1024, 1024, 100.0], $progressEvents[1]);
    }

    public function testDownloadProgressCallbackInvoked(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);

        $mockHttp->expects($this->once())
            ->method('download')
            ->willReturnCallback(function ($config, $url, $destination, $progress) {
                if ($progress !== null) {
                    $progress(500, 1000, 50.0);
                    $progress(1000, 1000, 100.0);
                }
                return true;
            });

        $downloadEvents = [];
        $callback = function (int $downloaded, int $total, float $pct) use (&$downloadEvents) {
            $downloadEvents[] = [$downloaded, $total, $pct];
        };

        $config = Telegram::create('TOKEN')
            ->withHttpClient($mockHttp)
            ->build();

        $bot = new Telegram($config);

        $result = $bot->downloadFile('photos/test.jpg', 'temp.jpg', progress: $callback);

        $this->assertInstanceOf(\Tueen\Telegram\Types\Custom\BooleanResult::class, $result);
        $this->assertTrue($result->isTrue);
        $this->assertCount(2, $downloadEvents);
        $this->assertSame([500, 1000, 50.0], $downloadEvents[0]);
        $this->assertSame([1000, 1000, 100.0], $downloadEvents[1]);
    }

    public function testRequestOptionsViaUnderscoreParameter(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);
        $mockHttp->expects($this->once())
            ->method('send')
            ->willReturnCallback(function ($config, Request $req) {
                $this->assertSame(45.0, $req->timeout);
                $this->assertSame(10.0, $req->connectTimeout);
                $this->assertArrayNotHasKey('_', $req->parameters);
                $this->assertNotNull($req->uploadProgress);
                return new Response(statusCode: 200, data: ['ok' => true, 'result' => true]);
            });

        $config = Telegram::create('TOKEN')
            ->withHttpClient($mockHttp)
            ->build();

        $bot = new Telegram($config);

        $options = \Tueen\Telegram\Client\RequestOptions::make()
            ->timeout(45.0)
            ->connectTimeout(10.0)
            ->onUploadProgress(fn($u, $t) => null);

        $bot->sendMessage(chatId: 12345, text: 'Hello Options', _: $options);
    }
}
