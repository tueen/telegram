<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Client\HttpClientInterface;
use Tueen\Telegram\Client\Response;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Custom\ArrayResult;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Types\Custom\IntegerResult;
use Tueen\Telegram\Types\Custom\StringResult;
use Tueen\Telegram\Types\Update;

class CustomResultTypesTest extends TestCase
{
    public function testBooleanResult(): void
    {
        $trueRes = new BooleanResult(true);
        $this->assertTrue($trueRes->value);
        $this->assertTrue($trueRes->isTrue());
        $this->assertFalse($trueRes->isFalse());
        $this->assertTrue($trueRes->getValue());
        $this->assertTrue($trueRes->toBool());
        $this->assertSame('true', (string)$trueRes);
        $this->assertSame(['value' => true], $trueRes->toArray());
        $this->assertSame(true, $trueRes->jsonSerialize());
        $this->assertTrue($trueRes['value']);

        $falseRes = new BooleanResult(false);
        $this->assertFalse($falseRes->value);
        $this->assertFalse($falseRes->isTrue());
        $this->assertTrue($falseRes->isFalse());
        $this->assertFalse($falseRes->toBool());
        $this->assertSame('false', (string)$falseRes);
    }

    public function testIntegerResult(): void
    {
        $intRes = new IntegerResult(42);
        $this->assertSame(42, $intRes->value);
        $this->assertSame(42, $intRes->getValue());
        $this->assertSame(42, $intRes->toInt());
        $this->assertTrue($intRes->isPositive());
        $this->assertFalse($intRes->isZero());
        $this->assertSame('42', (string)$intRes);
        $this->assertSame(42, $intRes->jsonSerialize());
        $this->assertSame(['value' => 42], $intRes->toArray());
        $this->assertSame(42, $intRes['value']);

        $zeroRes = new IntegerResult(0);
        $this->assertTrue($zeroRes->isZero());
        $this->assertFalse($zeroRes->isPositive());
    }

    public function testStringResult(): void
    {
        $strRes = new StringResult('https://t.me/+AbCdEf');
        $this->assertSame('https://t.me/+AbCdEf', $strRes->value);
        $this->assertSame('https://t.me/+AbCdEf', $strRes->getValue());
        $this->assertSame('https://t.me/+AbCdEf', $strRes->toString());
        $this->assertSame('https://t.me/+AbCdEf', (string)$strRes);
        $this->assertSame(20, $strRes->length());
        $this->assertFalse($strRes->isEmpty());
        $this->assertTrue($strRes->isNotEmpty());
        $this->assertTrue($strRes->contains('+AbCdEf'));
        $this->assertTrue($strRes->startsWith('https://'));
        $this->assertTrue($strRes->endsWith('AbCdEf'));
        $this->assertSame(['value' => 'https://t.me/+AbCdEf'], $strRes->toArray());
        $this->assertSame('https://t.me/+AbCdEf', $strRes->jsonSerialize());
        $this->assertSame('https://t.me/+AbCdEf', $strRes['value']);
    }

    public function testArrayResult(): void
    {
        $items = [
            new Update(['update_id' => 101]),
            new Update(['update_id' => 102]),
            new Update(['update_id' => 103]),
        ];

        $arrResult = new ArrayResult($items);

        $this->assertCount(3, $arrResult);
        $this->assertFalse($arrResult->isEmpty());
        $this->assertTrue($arrResult->isNotEmpty());
        $this->assertSame(101, $arrResult->first()->updateId);
        $this->assertSame(103, $arrResult->last()->updateId);
        $this->assertSame(102, $arrResult->get(1)->updateId);
        $this->assertSame(101, $arrResult[0]->updateId);

        // Iteration
        $ids = [];
        foreach ($arrResult as $upd) {
            $ids[] = $upd->updateId;
        }
        $this->assertSame([101, 102, 103], $ids);

        // Pluck
        $this->assertSame([101, 102, 103], $arrResult->pluck('updateId'));

        // Map
        $mapped = $arrResult->map(fn(Update $u) => $u->updateId * 2);
        $this->assertInstanceOf(ArrayResult::class, $mapped);
        $this->assertSame([202, 204, 206], $mapped->all());

        // Filter
        $filtered = $arrResult->filter(fn(Update $u) => $u->updateId > 101);
        $this->assertCount(2, $filtered);
        $this->assertSame(102, $filtered->first()->updateId);
    }

    public function testTelegramMethodsReturnCustomResultObjects(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);
        $mockHttp->method('send')->willReturnCallback(function ($cfg, $req) {
            return match ($req->endpoint) {
                'deleteWebhook' => new Response(200, ['ok' => true, 'result' => true]),
                'getChatMemberCount' => new Response(200, ['ok' => true, 'result' => 350]),
                'exportChatInviteLink' => new Response(200, ['ok' => true, 'result' => 'https://t.me/+joinChat']),
                'getUpdates' => new Response(200, ['ok' => true, 'result' => [
                    ['update_id' => 1],
                    ['update_id' => 2],
                ]]),
                default => new Response(200, ['ok' => true, 'result' => true]),
            };
        });

        $bot = new Telegram(Telegram::create('TOKEN')->withHttpClient($mockHttp)->build());

        // 1. Boolean return
        $delResult = $bot->deleteWebhook();
        $this->assertInstanceOf(BooleanResult::class, $delResult);
        $this->assertTrue($delResult->isTrue());

        // 2. Integer return
        $countResult = $bot->getChatMemberCount(chatId: 12345);
        $this->assertInstanceOf(IntegerResult::class, $countResult);
        $this->assertSame(350, $countResult->toInt());

        // 3. String return
        $linkResult = $bot->exportChatInviteLink(chatId: 12345);
        $this->assertInstanceOf(StringResult::class, $linkResult);
        $this->assertSame('https://t.me/+joinChat', $linkResult->toString());

        // 4. Array return
        $updates = $bot->getUpdates();
        $this->assertInstanceOf(ArrayResult::class, $updates);
        $this->assertCount(2, $updates);
        $this->assertInstanceOf(Update::class, $updates[0]);
        $this->assertSame(1, $updates[0]->updateId);
        $this->assertSame(2, $updates[1]->updateId);
    }
}
