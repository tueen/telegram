# Text Formatting & Escaping

Telegram supports rich text formatting via **HTML**, **MarkdownV2**, and legacy **Markdown**. However, unescaped characters (such as `_`, `*`, `[`, `<`, `>`, `&`) frequently cause Telegram API errors like `400 Bad Request: can't parse entities`.

`tueen/telegram` provides two complementary utilities strictly adhering to the [Telegram Bot API Formatting Options specification](https://core.telegram.org/bots/api#formatting-options):
1. **`Text` Builder**: A safe, fluent, nesting-capable builder for constructing styled messages.
2. **`Escape` Helper**: Low-level escaping functions for user-generated strings.

---

## 🎨 1. Fluent `Text` Builder

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

You can generate MarkdownV2 without manually escaping the 18 reserved Telegram characters:

```php
$message = Text::markdownV2()
    ->bold('System Alert!')->line()
    ->spoiler('Secret token inside')->line()
    ->expandableBlockquote("Log line 1: OK\nLog line 2: WARNING\nLog line 3: SUCCESS")
    ->line()
    ->pre("<?php\necho 'Hello World!';", language: 'php');
```

---

## 🔗 2. Nested Formatting Styles

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

## 🛡️ 3. Low-Level Escaping with `Escape`

When constructing strings manually or templating with Blade/Twig, use `Tueen\Telegram\Formatting\Escape`:

```php
use Tueen\Telegram\Formatting\Escape;

// 1. Escaping for HTML
$safeHtml = Escape::html("<script>alert('xss');</script> & Bob");
// Returns: &lt;script&gt;alert('xss');&lt;/script&gt; &amp; Bob

// 2. Escaping for MarkdownV2 (escapes 18 reserved Telegram characters)
$safeMarkdown = Escape::markdownV2("Price is [10.00$] *special*");
// Returns: Price is \[10\.00\$\] \*special\*

// 3. Escaping URL query components
$safeUrl = Escape::urlParam("https://example.com?query=hello world");
```

---

## 🧭 4. Formatting API Catalog

Below is the complete reference of methods available on the `Text` builder and `Escape` helper.

### 🎨 `Text` Builder (`Tueen\Telegram\Formatting\Text`)

<ApiGroup description="Fluent builder for safe HTML and MarkdownV2 styled messages.">
  <ApiCard
    sig="bold(string|callable|Text $content): self"
    returns="self"
    badge="Formatting"
    desc="Wraps content in bold weight text."
  />
  <ApiCard
    sig="italic(string|callable|Text $content): self"
    returns="self"
    badge="Formatting"
    desc="Wraps content in italicized text."
  />
  <ApiCard
    sig="underline(string|callable|Text $content): self"
    returns="self"
    badge="Formatting"
    desc="Wraps content in underlined text."
  />
  <ApiCard
    sig="strikethrough(string|callable|Text $content): self"
    returns="self"
    badge="Formatting"
    desc="Wraps content in strikethrough text."
  />
  <ApiCard
    sig="spoiler(string|callable|Text $content): self"
    returns="self"
    badge="Formatting"
    desc="Wraps content in hidden spoiler tags."
  />
  <ApiCard
    sig="blockquote(string|callable|Text $content): self"
    returns="self"
    badge="Block"
    desc="Renders a block quote quotation."
  />
  <ApiCard
    sig="expandableBlockquote(string|callable|Text $content): self"
    returns="self"
    badge="Block"
    desc="Renders a collapsible expandable block quote."
  />
  <ApiCard
    sig="code(string $code): self"
    returns="self"
    badge="Inline"
    desc="Renders inline monospace code."
  />
  <ApiCard
    sig="pre(string $code, ?string $language = null): self"
    returns="self"
    badge="Block"
    desc="Renders a pre-formatted syntax code block with optional language highlighting."
  />
  <ApiCard
    sig="link(string|callable|Text $content, string $url): self"
    returns="self"
    badge="Inline"
    desc="Renders an inline hyperlink with clickable label text."
  />
  <ApiCard
    sig="userMention(string|callable|Text $content, int $userId): self"
    returns="self"
    badge="Inline"
    desc="Renders a direct user mention link targeting a Telegram user ID."
  />
  <ApiCard
    sig="customEmoji(string $emoji, string $customEmojiId): self"
    returns="self"
    badge="Inline"
    desc="Renders an animated custom Telegram emoji with its unique custom emoji ID."
  />
  <ApiCard
    sig="plain(string $text): self"
    returns="self"
    badge="Content"
    desc="Appends plain text, automatically escaping any reserved formatting characters."
  />
  <ApiCard
    sig="raw(string $string): self"
    returns="self"
    badge="Content"
    desc="Appends raw text without escaping (use only with pre-sanitized strings)."
  />
  <ApiCard
    sig="line(string|callable|Text|null $content = null): self"
    returns="self"
    badge="Layout"
    desc="Appends content followed by a newline."
  />
  <ApiCard
    sig="br(int $count = 1): self"
    returns="self"
    badge="Layout"
    desc="Appends one or more blank line breaks."
  />
  <ApiCard
    sig="hr(int $length = 25): self"
    returns="self"
    badge="Layout"
    desc="Appends a horizontal rule divider line."
  />
</ApiGroup>

---

### 🛡️ `Escape` Helper (`Tueen\Telegram\Formatting\Escape`)

<ApiGroup description="Low-level escaping routines adhering to the Telegram Bot API specification.">
  <ApiCard
    sig="Escape::html(string $text): string"
    returns="string"
    badge="Escaping"
    desc="Escapes HTML special characters (&amp;, &lt;, &gt;, &quot;) for Telegram HTML parse mode."
  />
  <ApiCard
    sig="Escape::markdownV2(string $text): string"
    returns="string"
    badge="Escaping"
    desc="Escapes all 18 reserved characters for MarkdownV2."
  />
  <ApiCard
    sig="Escape::markdown(string $text): string"
    returns="string"
    badge="Escaping"
    desc="Escapes legacy Markdown characters."
  />
</ApiGroup>
