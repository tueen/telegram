<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Types\InlineKeyboardButton;
use Tueen\Telegram\Types\InlineKeyboardMarkup;
use Tueen\Telegram\Types\KeyboardButton;
use Tueen\Telegram\Types\ReplyKeyboardMarkup;

class KeyboardConcernsTest extends TestCase
{
    public function testInlineKeyboardMarkupHelpers(): void
    {
        $markup = new InlineKeyboardMarkup(['inline_keyboard' => []]);

        $this->assertTrue($markup->isEmpty);
        $this->assertSame(0, $markup->rowCount);
        $this->assertSame(0, $markup->buttonCount);

        $btnPrev = new InlineKeyboardButton(['text' => 'Prev', 'callback_data' => 'prev']);
        $btnNext = new InlineKeyboardButton(['text' => 'Next', 'callback_data' => 'next']);
        $btnCancel = new InlineKeyboardButton(['text' => 'Cancel', 'callback_data' => 'cancel']);

        // addRow
        $markup->addRow($btnPrev, $btnNext);
        $this->assertFalse($markup->isEmpty);
        $this->assertSame(1, $markup->rowCount);
        $this->assertSame(2, $markup->buttonCount);

        // add another row
        $markup->addRow($btnCancel);
        $this->assertSame(2, $markup->rowCount);
        $this->assertSame(3, $markup->buttonCount);

        // prependRow
        $btnTop = new InlineKeyboardButton(['text' => 'Top Info', 'callback_data' => 'top']);
        $markup->prependRow($btnTop);
        $this->assertSame(3, $markup->rowCount);
        $this->assertSame('Top Info', $markup->inlineKeyboard[0][0]->text);

        // insertRow at index 1
        $btnInsert = new InlineKeyboardButton(['text' => 'Inserted', 'callback_data' => 'ins']);
        $markup->insertRow(1, $btnInsert);
        $this->assertSame(4, $markup->rowCount);
        $this->assertSame('Inserted', $markup->inlineKeyboard[1][0]->text);

        // removeRow at index 1
        $markup->removeRow(1);
        $this->assertSame(3, $markup->rowCount);
        $this->assertSame('Top Info', $markup->inlineKeyboard[0][0]->text);
        $this->assertSame('Prev', $markup->inlineKeyboard[1][0]->text);

        // reverseRows (vertical flip)
        $markup->reverseRows();
        $this->assertSame('Cancel', $markup->inlineKeyboard[0][0]->text);
        $this->assertSame('Top Info', $markup->inlineKeyboard[2][0]->text);

        // restore vertical order
        $markup->reverseRows();
        $this->assertSame('Top Info', $markup->inlineKeyboard[0][0]->text);
        $this->assertSame('Prev', $markup->inlineKeyboard[1][0]->text);
        $this->assertSame('Next', $markup->inlineKeyboard[1][1]->text);

        // reverseRow (specific row - e.g. row 1 for RTL swap)
        $markup->reverseRow(1);
        $this->assertSame('Next', $markup->inlineKeyboard[1][0]->text);
        $this->assertSame('Prev', $markup->inlineKeyboard[1][1]->text);

        // reverseRow with negative index (-2 is row 1)
        $markup->reverseRow(-2);
        $this->assertSame('Prev', $markup->inlineKeyboard[1][0]->text);
        $this->assertSame('Next', $markup->inlineKeyboard[1][1]->text);

        // reverseColumns / rtl (horizontal flip of all rows)
        $markup->reverseColumns();
        $this->assertSame('Next', $markup->inlineKeyboard[1][0]->text);
        $this->assertSame('Prev', $markup->inlineKeyboard[1][1]->text);

        // rtl alias flips it back
        $markup->rtl();
        $this->assertSame('Prev', $markup->inlineKeyboard[1][0]->text);
        $this->assertSame('Next', $markup->inlineKeyboard[1][1]->text);

        // filterButtons: keep buttons that are not 'Cancel'
        $markup->filterButtons(fn(InlineKeyboardButton $b) => $b->text !== 'Cancel');
        $this->assertSame(2, $markup->rowCount);
        $this->assertSame(3, $markup->buttonCount);

        // mapButtons
        $markup->mapButtons(fn(InlineKeyboardButton $b) => new InlineKeyboardButton([
            'text' => '[' . $b->text . ']',
            'callback_data' => $b->callbackData,
        ]));
        $this->assertSame('[Top Info]', $markup->inlineKeyboard[0][0]->text);
        $this->assertSame('[Prev]', $markup->inlineKeyboard[1][0]->text);
    }

    public function testReplyKeyboardMarkupHelpers(): void
    {
        $markup = new ReplyKeyboardMarkup(['keyboard' => []]);

        $this->assertTrue($markup->isEmpty);
        $this->assertSame(0, $markup->rowCount);
        $this->assertSame(0, $markup->buttonCount);

        $btn1 = new KeyboardButton(['text' => 'Option 1']);
        $btn2 = new KeyboardButton(['text' => 'Option 2']);
        $btnBack = new KeyboardButton(['text' => 'Back']);

        // addRow
        $markup->addRow($btn1, $btn2);
        $this->assertFalse($markup->isEmpty);
        $this->assertSame(1, $markup->rowCount);
        $this->assertSame(2, $markup->buttonCount);

        // addRow with single button
        $markup->addRow($btnBack);
        $this->assertSame(2, $markup->rowCount);
        $this->assertSame(3, $markup->buttonCount);

        // prependRow
        $btnHelp = new KeyboardButton(['text' => 'Help']);
        $markup->prependRow($btnHelp);
        $this->assertSame(3, $markup->rowCount);
        $this->assertSame('Help', $markup->keyboard[0][0]->text);

        // reverseColumns (RTL)
        $markup->reverseColumns();
        $this->assertSame('Option 2', $markup->keyboard[1][0]->text);
        $this->assertSame('Option 1', $markup->keyboard[1][1]->text);

        // rtl() alias
        $markup->rtl();
        $this->assertSame('Option 1', $markup->keyboard[1][0]->text);
        $this->assertSame('Option 2', $markup->keyboard[1][1]->text);

        // reverseRow
        $markup->reverseRow(1);
        $this->assertSame('Option 2', $markup->keyboard[1][0]->text);
        $this->assertSame('Option 1', $markup->keyboard[1][1]->text);

        // reverseRows (vertical flip)
        $markup->reverseRows();
        $this->assertSame('Back', $markup->keyboard[0][0]->text);
        $this->assertSame('Help', $markup->keyboard[2][0]->text);

        // removeRow
        $markup->removeRow(0);
        $this->assertSame(2, $markup->rowCount);
        $this->assertSame('Option 2', $markup->keyboard[0][0]->text);
    }

    public function testUninitializedMarkupDefaults(): void
    {
        // When constructed empty without explicit data
        $inline = new InlineKeyboardMarkup();
        $this->assertTrue($inline->isEmpty);
        $this->assertSame(0, $inline->rowCount);
        $this->assertSame(0, $inline->buttonCount);

        $btn = new InlineKeyboardButton(['text' => 'OK', 'callback_data' => 'ok']);
        $inline->addRow($btn);
        $this->assertSame(1, $inline->rowCount);
        $this->assertSame(1, $inline->buttonCount);
        $this->assertFalse($inline->isEmpty);

        $reply = new ReplyKeyboardMarkup();
        $this->assertTrue($reply->isEmpty);
        $this->assertSame(0, $reply->rowCount);
        $this->assertSame(0, $reply->buttonCount);

        $rbtn = new KeyboardButton(['text' => 'Send']);
        $reply->addRow($rbtn);
        $this->assertSame(1, $reply->rowCount);
        $this->assertSame(1, $reply->buttonCount);
        $this->assertFalse($reply->isEmpty);
    }
}
