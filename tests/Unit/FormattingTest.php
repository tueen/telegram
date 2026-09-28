<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Enums\ParseMode;
use Tueen\Telegram\Formatting\Escape;
use Tueen\Telegram\Formatting\Text;
use Tueen\Telegram\Keyboards\InlineKeyboard;
use Tueen\Telegram\Telegram;

class FormattingTest extends TestCase
{
    public function testEscapeHtml(): void
    {
        $input = '<script>alert("hello & welcome");</script>';
        $escaped = Escape::html($input);

        $this->assertSame('&lt;script&gt;alert(&quot;hello &amp; welcome&quot;);&lt;/script&gt;', $escaped);
    }

    public function testEscapeMarkdownV2(): void
    {
        $input = 'Hello *world* [link] (test) ~strike~ `code` >quote #tag +1 -2 =3 |pipe| {brace} .dot !bang \\slash _italic_';
        $escaped = Escape::markdownV2($input);

        $this->assertStringContainsString('\\*world\\*', $escaped);
        $this->assertStringContainsString('\\[link\\]', $escaped);
        $this->assertStringContainsString('\\(test\\)', $escaped);
        $this->assertStringContainsString('\\~strike\\~', $escaped);
        $this->assertStringContainsString('\\`code\\`', $escaped);
        $this->assertStringContainsString('\\>quote', $escaped);
        $this->assertStringContainsString('\\#tag', $escaped);
        $this->assertStringContainsString('\\+1', $escaped);
        $this->assertStringContainsString('\\-2', $escaped);
        $this->assertStringContainsString('\\=3', $escaped);
        $this->assertStringContainsString('\\|pipe\\|', $escaped);
        $this->assertStringContainsString('\\{brace\\}', $escaped);
        $this->assertStringContainsString('\\.dot', $escaped);
        $this->assertStringContainsString('\\!bang', $escaped);
        $this->assertStringContainsString('\\\\slash', $escaped);
        $this->assertStringContainsString('\\_italic\\_', $escaped);
    }

    public function testEscapeMarkdownV2CodeAndLinkAndCustomEmoji(): void
    {
        $code = 'let x = `test` \\ foo;';
        $escapedCode = Escape::markdownV2Code($code);
        $this->assertSame('let x = \\`test\\` \\\\ foo;', $escapedCode);

        $link = 'https://example.com/path(1)\\2';
        $escapedLink = Escape::markdownV2Link($link);
        $this->assertSame('https://example.com/path(1\\)\\\\2', $escapedLink);

        $emoji = '👍]test\\icon';
        $escapedEmoji = Escape::markdownV2CustomEmoji($emoji);
        $this->assertSame('👍\\]test\\\\icon', $escapedEmoji);
    }

    public function testEscapeLegacyMarkdown(): void
    {
        $text = 'Test *bold* _italic_ `code` [link]';
        $escaped = Escape::markdown($text);
        $this->assertSame('Test \\*bold\\* \\_italic\\_ \\`code\\` \\[link]', $escaped);

        $code = '`var` \\ test';
        $this->assertSame('\\`var\\` \\\\ test', Escape::markdownCode($code));

        $link = 'https://example.com/a(1)\\2';
        $this->assertSame('https://example.com/a(1\\)\\\\2', Escape::markdownLink($link));
    }

    public function testTextHtmlBuilder(): void
    {
        $text = Text::html()
            ->bold('Welcome <User>!')
            ->line()
            ->italic('Special Offer & Deals')
            ->line()
            ->underline('Important')
            ->space()
            ->strikethrough('Old Price')
            ->space()
            ->spoiler('Secret Bonus')
            ->line()
            ->code('git pull origin main')
            ->line()
            ->pre("def hello():\n    print('Hi')", 'python')
            ->link('Tueen Official', 'https://tueen.org?ref=1&test=2')
            ->space()
            ->userMention('Admin', 123456789)
            ->space()
            ->customEmoji('👍', '5368324170671202286')
            ->line()
            ->blockquote("Quote Line 1\nQuote Line 2")
            ->line()
            ->expandableBlockquote("Expandable Quote");

        $html = (string)$text;

        $this->assertSame(ParseMode::HTML, $text->parseMode());
        $this->assertStringContainsString('<b>Welcome &lt;User&gt;!</b>', $html);
        $this->assertStringContainsString('<i>Special Offer &amp; Deals</i>', $html);
        $this->assertStringContainsString('<u>Important</u>', $html);
        $this->assertStringContainsString('<s>Old Price</s>', $html);
        $this->assertStringContainsString('<tg-spoiler>Secret Bonus</tg-spoiler>', $html);
        $this->assertStringContainsString('<code>git pull origin main</code>', $html);
        $this->assertStringContainsString("<pre><code class=\"language-python\">def hello():\n    print('Hi')</code></pre>", $html);
        $this->assertStringContainsString('<a href="https://tueen.org?ref=1&amp;test=2">Tueen Official</a>', $html);
        $this->assertStringContainsString('<a href="tg://user?id=123456789">Admin</a>', $html);
        $this->assertStringContainsString('<tg-emoji emoji-id="5368324170671202286">👍</tg-emoji>', $html);
        $this->assertStringContainsString("<blockquote>Quote Line 1\nQuote Line 2</blockquote>", $html);
        $this->assertStringContainsString('<blockquote expandable>Expandable Quote</blockquote>', $html);
    }

    public function testTextMarkdownV2Builder(): void
    {
        $text = Text::markdownV2()
            ->bold('Bold!')
            ->line()
            ->italic('Italic!')
            ->line()
            ->underline('Underline!')
            ->line()
            ->strikethrough('Strike!')
            ->line()
            ->spoiler('Hidden!')
            ->line()
            ->code('console.log("hi");')
            ->line()
            ->pre("echo 123;", 'bash')
            ->line()
            ->link('Link (click)', 'https://example.com/test(1)')
            ->line()
            ->userMention('User', 987654)
            ->line()
            ->customEmoji('⭐', '54321')
            ->line()
            ->blockquote("Line 1\nLine 2")
            ->line()
            ->expandableBlockquote("Expandable Line");

        $md = (string)$text;

        $this->assertSame(ParseMode::MARKDOWN_V2, $text->parseMode());
        $this->assertStringContainsString('*Bold\\!*', $md);
        $this->assertStringContainsString('_Italic\\!_', $md);
        $this->assertStringContainsString('__Underline\\!__', $md);
        $this->assertStringContainsString('~Strike\\!~', $md);
        $this->assertStringContainsString('||Hidden\\!||', $md);
        $this->assertStringContainsString('`console.log("hi");`', $md);
        $this->assertStringContainsString("```bash\necho 123;\n```", $md);
        $this->assertStringContainsString('[Link \\(click\\)](https://example.com/test(1\\))', $md);
        $this->assertStringContainsString('[User](tg://user?id=987654)', $md);
        $this->assertStringContainsString('![⭐](tg://emoji?id=54321)', $md);
        $this->assertStringContainsString(">Line 1\n>Line 2", $md);
        $this->assertStringContainsString('**>Expandable Line', $md);
    }

    public function testNestedStyling(): void
    {
        // HTML nested: bold italic
        $htmlText = Text::html()->bold(fn(Text $t) => $t->italic('Bold and Italic'));
        $this->assertSame('<b><i>Bold and Italic</i></b>', (string)$htmlText);

        // MarkdownV2 nested: bold spoiler
        $mdText = Text::markdownV2()->bold(fn(Text $t) => $t->spoiler('Bold and Spoiler'));
        $this->assertSame('*||Bold and Spoiler||*', (string)$mdText);

        // Nested link with bold label
        $link = Text::html()->link(fn(Text $t) => $t->bold('Click Here'), 'https://example.com');
        $this->assertSame('<a href="https://example.com"><b>Click Here</b></a>', (string)$link);
    }

    public function testTelegramEntityHelpers(): void
    {
        $text = Text::html()
            ->mention('@tueen_bot')
            ->space()
            ->hashtag('#php85')
            ->space()
            ->cashtag('$USD')
            ->space()
            ->botCommand('/start')
            ->line()
            ->email('support@tueen.org')
            ->line()
            ->phone('+1234567890');

        $output = (string)$text;

        $this->assertStringContainsString('@tueen_bot', $output);
        $this->assertStringContainsString('#php85', $output);
        $this->assertStringContainsString('$USD', $output);
        $this->assertStringContainsString('/start', $output);
        $this->assertStringContainsString('<a href="mailto:support@tueen.org">support@tueen.org</a>', $output);
        $this->assertStringContainsString('<a href="tel:+1234567890">+1234567890</a>', $output);
    }

    public function testTextUtilitiesAndLimits(): void
    {
        $text = Text::html('Hello World');

        $this->assertSame(11, $text->length());
        $this->assertFalse($text->isEmpty());
        $this->assertTrue($text->isNotEmpty());
        $this->assertTrue($text->isWithinLimit(4096));

        $text->truncate(8, '...');
        $this->assertSame('Hello...', (string)$text);

        $text->clear();
        $this->assertTrue($text->isEmpty());
        $this->assertSame(0, $text->length());
    }

    public function testDirectTextAndKeyboardPassingInClient(): void
    {
        $bot = Telegram::fake();

        $formatted = Text::html()
            ->bold('Invoice #100')
            ->line()
            ->plain('Total: $50');

        $keyboard = InlineKeyboard::make()
            ->callback('Pay Now', 'pay:100');

        $bot->sendMessage(
            chatId: 12345,
            text: $formatted,
            replyMarkup: $keyboard
        );

        $bot->assertSent('sendMessage', function (array $params) {
            $markup = is_string($params['reply_markup'] ?? null)
                ? json_decode($params['reply_markup'], true)
                : ($params['reply_markup'] ?? []);

            return $params['chat_id'] === 12345
                && $params['text'] === "<b>Invoice #100</b>\nTotal: $50"
                && $params['parse_mode'] === 'HTML'
                && isset($markup['inline_keyboard']);
        });
    }
}
