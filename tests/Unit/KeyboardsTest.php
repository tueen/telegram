<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Keyboards\InlineKeyboard;
use Tueen\Telegram\Keyboards\ReplyKeyboard;
use Tueen\Telegram\Types\InlineKeyboardMarkup;
use Tueen\Telegram\Types\ReplyKeyboardMarkup;
use Tueen\Telegram\Types\ReplyKeyboardRemove;

class KeyboardsTest extends TestCase
{
    public function testInlineKeyboardBuilder(): void
    {
        $keyboard = InlineKeyboard::make()
            ->row()
                ->callback('Option A', 'opt_a')
                ->callback('Option B', 'opt_b')
            ->row()
                ->url('Tueen Site', 'https://tueen.org')
                ->webApp('Mini App', 'https://app.tueen.org')
            ->row()
                ->copyText('Copy Code', 'SECRET_CODE_123')
                ->switchInlineQuery('Share', 'query')
                ->pay('Pay $5');

        $markup = $keyboard->build();

        $this->assertInstanceOf(InlineKeyboardMarkup::class, $markup);
        $array = $markup->toArray();

        $this->assertCount(3, $array['inline_keyboard']);
        $this->assertSame('Option A', $array['inline_keyboard'][0][0]['text']);
        $this->assertSame('opt_a', $array['inline_keyboard'][0][0]['callback_data']);
        $this->assertSame('https://tueen.org', $array['inline_keyboard'][1][0]['url']);
        $this->assertSame('https://app.tueen.org', $array['inline_keyboard'][1][1]['web_app']['url']);
        $this->assertSame('SECRET_CODE_123', $array['inline_keyboard'][2][0]['copy_text']['text']);
        $this->assertSame('query', $array['inline_keyboard'][2][1]['switch_inline_query']);
        $this->assertTrue($array['inline_keyboard'][2][2]['pay']);
    }

    public function testInlineKeyboardChunk(): void
    {
        $keyboard = InlineKeyboard::make()
            ->callback('1', '1')
            ->callback('2', '2')
            ->callback('3', '3')
            ->callback('4', '4')
            ->callback('5', '5')
            ->chunk(2);

        $markup = $keyboard->build();
        $array = $markup->toArray();

        $this->assertCount(3, $array['inline_keyboard']);
        $this->assertCount(2, $array['inline_keyboard'][0]);
        $this->assertCount(2, $array['inline_keyboard'][1]);
        $this->assertCount(1, $array['inline_keyboard'][2]);
    }

    public function testReplyKeyboardBuilder(): void
    {
        $keyboard = ReplyKeyboard::make()
            ->resize(true)
            ->oneTime(true)
            ->persistent(true)
            ->placeholder('Select an option...')
            ->row()
                ->requestContact('Send Contact')
                ->requestLocation('Send Location')
            ->row()
                ->requestPoll('Create Poll', type: 'quiz')
                ->requestChat('Select Channel', requestId: 1, chatIsChannel: true)
                ->requestUsers('Select Bot', requestId: 2, userIsBot: true)
            ->row()
                ->webApp('Open App', 'https://webapp.test')
                ->text('Plain Button');

        $markup = $keyboard->build();

        $this->assertInstanceOf(ReplyKeyboardMarkup::class, $markup);
        $array = $markup->toArray();

        $this->assertTrue($array['resize_keyboard']);
        $this->assertTrue($array['one_time_keyboard']);
        $this->assertTrue($array['is_persistent']);
        $this->assertSame('Select an option...', $array['input_field_placeholder']);
        $this->assertCount(3, $array['keyboard']);
        $this->assertTrue($array['keyboard'][0][0]['request_contact']);
        $this->assertTrue($array['keyboard'][0][1]['request_location']);
        $this->assertSame('quiz', $array['keyboard'][1][0]['request_poll']['type']);
        $this->assertTrue($array['keyboard'][1][1]['request_chat']['chat_is_channel']);
        $this->assertTrue($array['keyboard'][1][2]['request_users']['user_is_bot']);
    }

    public function testReplyKeyboardRemove(): void
    {
        $remove = ReplyKeyboard::remove(selective: true);

        $this->assertInstanceOf(ReplyKeyboardRemove::class, $remove);
        $array = $remove->toArray();
        $this->assertTrue($array['remove_keyboard']);
        $this->assertTrue($array['selective']);
    }
}
