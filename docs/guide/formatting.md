# Text Formatting & Escaping

Telegram supports rich text formatting via **HTML**, **MarkdownV2**, and legacy **Markdown**. However, unescaped characters (such as `_`, `*`, `[`, `<`, `>`, `&`) frequently cause Telegram API errors like `400 Bad Request: can't parse entities`.

`tueen/telegram` provides two complementary utilities strictly adhering to the [Telegram Bot API Formatting Options specification](https://core.telegram.org/bots/api#formatting-options):
1. **`Text` Builder**: A safe, fluent, nesting-capable builder for constructing styled messages.
2. **`Escape` Helper**: Low-level escaping functions for user-generated strings.

---

## 1. Fluent `Text` Builder

The `Text` builder lets you chain formatting methods while automatically escaping any dynamic or user-supplied variables.

### Direct API Integration

You can pass `Text` instances directly into Telegram methods like `$bot->sendMessage()` or `$bot->sendPhoto(caption: ...)`. Tueen **automatically casts the text to a string** and **auto-sets `parseMode`** to the builder's mode:

```php
use Tueen\Telegram\Formatting\Text;

// No need to manually cast (string) or specify parseMode:
$bot->sendMessage(
    chatId: $chatId,
    text: Text::html()
        ->bold('Invoice #100')->line()
        ->plain('Total: $50.00')
);
```

### HTML Formatting (`Text::html`)

```php
$message = Text::html()
    ->bold('Order Confirmation')->line()
    ->line()
    ->plain('User: ')->userMention($user->name, $user->id)->line()
    ->plain('Item: ')->bold($item->name)->line()
    ->plain('Price: ')->code("$" . number_format($item->price, 2))->line()
    ->line()
    ->blockquote('Thank you for shopping with us!')
    ->line()
    ->link('Track Package', 'https://track.example.com/12345');
```

### MarkdownV2 Formatting (`Text::markdownV2`)

You can generate bulletproof MarkdownV2 without manually escaping the 18 reserved Telegram characters:

```php
$message = Text::markdownV2()
    ->bold('System Alert!')->line()
    ->spoiler('Secret token inside')->line()
    ->expandableBlockquote("Log line 1: OK\nLog line 2: WARNING\nLog line 3: SUCCESS")
    ->line()
    ->pre("<?php\necho 'Hello World!';", language: 'php');
```

---

## 2. Nested Formatting Styles

Telegram allows entities to be nested (e.g. bold italic text or links with bold labels). `Text` supports nesting using **Closures**, sub-`Text` instances, or strings:

```php
// Bold italic text: <b><i>Bold and Italic</i></b>
$text = Text::html()->bold(fn(Text $t) => $t->italic('Bold and Italic'));

// Bold spoiler in MarkdownV2: *||Secret||*
$text = Text::markdownV2()->bold(fn(Text $t) => $t->spoiler('Secret'));

// Hyperlink with bold label
$link = Text::html()->link(fn(Text $t) => $t->bold('Click Here'), 'https://example.com');
```

---

## 3. Supported Formatting Styles

Both `Text::html()` and `Text::markdownV2()` support the full range of Telegram formatting features:

| Method | HTML Output | MarkdownV2 Output | Description |
| :--- | :--- | :--- | :--- |
| `->bold($content)` | `<b>...</b>` | `*...*` | Bold weight text (supports nesting) |
| `->italic($content)` | `<i>...</i>` | `_..._` | Italicized text (supports nesting) |
| `->underline($content)` | `<u>...</u>` | `__...__` | Underlined text (supports nesting) |
| `->strikethrough($content)` | `<s>...</s>` | `~...~` | Strikethrough text (supports nesting) |
| `->spoiler($content)` | `<tg-spoiler>...</tg-spoiler>` | `\|\|...\|\|` | Hidden spoiler text (supports nesting) |
| `->blockquote($content)` | `<blockquote>...</blockquote>` | `>...` | Block quotation |
| `->expandableBlockquote($content)` | `<blockquote expandable>...</blockquote>` | `**>...` | Collapsible block quote |
| `->code($code)` | `<code>...</code>` | `` `...` `` | Inline monospace code |
| `->pre($code, $lang)` | `<pre><code class="...">...</code></pre>` | ```` ```lang ... ``` ```` | Pre-formatted code block |
| `->link($content, $url)` | `<a href="...">...</a>` | `[...] (...)` | Inline hyperlink (supports styled labels) |
| `->userMention($content, $userId)` | `<a href="tg://user?id=...">...</a>` | `[...] (tg://user?id=...)` | Mention user by Telegram ID |
| `->customEmoji($emoji, $id)` | `<tg-emoji emoji-id="...">...</tg-emoji>` | `![emoji](tg://emoji?id=...)` | Custom animated Telegram emoji |
| `->plain($text)` | Escaped text | Escaped text | Regular text, automatically escaped |
| `->raw($string)` | Unescaped string | Unescaped string | Raw text without escaping |

---

## 4. Entity Helpers & Dividers

```php
$text = Text::html()
    ->mention('@my_bot')                     // Adds @my_bot
    ->space()
    ->hashtag('#tueen')                      // Adds #tueen
    ->space()
    ->cashtag('$TON')                        // Adds $TON
    ->space()
    ->botCommand('/help')                    // Adds /help
    ->line()
    ->email('support@tueen.org')             // Creates mailto: link
    ->line()
    ->phone('+1234567890')                   // Creates tel: link
    ->br(2)                                  // Appends 2 blank lines
    ->hr(25);                                // Appends a horizontal divider (—————————)
```

---

## 5. Length & Character Limit Utilities

Telegram enforces strict character limits (e.g. 4096 characters for messages, 1024 characters for media captions):

```php
$text = Text::html()->bold('Very long text...');

echo $text->length();                       // UTF-8 character count
$text->isEmpty();                           // bool
$text->isNotEmpty();                        // bool
$text->isWithinLimit(4096);                 // true if <= 4096 characters

// Safely truncate to a maximum length with suffix:
$text->truncate(4096, '... [read more]');
```

---

## 6. Low-Level `Escape` Utility

When assembling strings manually or using existing templates, use `Escape` to sanitize user input:

```php
use Tueen\Telegram\Formatting\Escape;

// HTML escaping: replaces <, >, &, and " with &lt;, &gt;, &amp;, &quot;
$safeUserName = Escape::html($userInput);

// MarkdownV2 escaping: escapes all 18 Telegram special characters:
// _, *, [, ], (, ), ~, `, >, #, +, -, =, |, {, }, ., !
$safeComment = Escape::markdownV2($userInput);

// Code and Pre escaping: inside code blocks, only ` and \ need escaping
$safeCode = Escape::markdownV2Code($rawCode);

// Inline link URL escaping: inside link URLs, only ) and \ need escaping
$safeUrl = Escape::markdownV2Link($userUrl);

// Custom Emoji escaping: inside [...] only ] and \ need escaping
$safeEmoji = Escape::markdownV2CustomEmoji($rawEmoji);

// Legacy Markdown escaping
$safeLegacy = Escape::markdown($userInput);
```
