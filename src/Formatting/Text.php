<?php

declare(strict_types=1);

namespace Tueen\Telegram\Formatting;

use Closure;
use Stringable;
use Tueen\Telegram\Enums\ParseMode;

/**
 * Fluent builder for safe, structured Telegram formatted text.
 * Strictly adheres to Telegram Bot API 10.3 formatting rules.
 *
 * @link https://core.telegram.org/bots/api#formatting-options
 */
final class Text implements Stringable
{
    /** @var list<string> */
    private array $parts = [];

    public function __construct(
        private ParseMode $mode = ParseMode::HTML,
        string $initial = ''
    ) {
        if ($initial !== '') {
            $this->plain($initial);
        }
    }

    #[\NoDiscard]
    public static function html(string $initial = ''): self
    {
        return new self(ParseMode::HTML, $initial);
    }

    #[\NoDiscard]
    public static function markdownV2(string $initial = ''): self
    {
        return new self(ParseMode::MARKDOWN_V2, $initial);
    }

    #[\NoDiscard]
    public static function make(ParseMode $mode = ParseMode::HTML): self
    {
        return new self($mode);
    }

    /**
     * Resolves content, supporting nested Text builders, closures, and strings.
     */
    private function resolveContent(string|self|Closure $content): string
    {
        if ($content instanceof Closure) {
            $sub = new self($this->mode);
            $content($sub);
            return (string) $sub;
        }

        if ($content instanceof self) {
            return (string) $content;
        }

        return match ($this->mode) {
            ParseMode::HTML => Escape::html($content),
            ParseMode::MARKDOWN_V2 => Escape::markdownV2($content),
            ParseMode::MARKDOWN => Escape::markdown($content),
        };
    }

    /**
     * Appends plain text, automatically escaping any special characters.
     */
    public function plain(string $text): self
    {
        $this->parts[] = match ($this->mode) {
            ParseMode::HTML => Escape::html($text),
            ParseMode::MARKDOWN_V2 => Escape::markdownV2($text),
            ParseMode::MARKDOWN => Escape::markdown($text),
        };
        return $this;
    }

    /**
     * Appends raw, unescaped string. Use with caution.
     */
    public function raw(string $raw): self
    {
        $this->parts[] = $raw;
        return $this;
    }

    /**
     * Appends bold text (supports nested styles).
     */
    public function bold(string|self|Closure $text): self
    {
        $inner = $this->resolveContent($text);
        $this->parts[] = match ($this->mode) {
            ParseMode::HTML => '<b>' . $inner . '</b>',
            ParseMode::MARKDOWN_V2 => '*' . $inner . '*',
            ParseMode::MARKDOWN => '*' . $inner . '*',
        };
        return $this;
    }

    /**
     * Appends italic text (supports nested styles).
     */
    public function italic(string|self|Closure $text): self
    {
        $inner = $this->resolveContent($text);
        $this->parts[] = match ($this->mode) {
            ParseMode::HTML => '<i>' . $inner . '</i>',
            ParseMode::MARKDOWN_V2 => '_' . $inner . '_',
            ParseMode::MARKDOWN => '_' . $inner . '_',
        };
        return $this;
    }

    /**
     * Appends underlined text (supports nested styles).
     */
    public function underline(string|self|Closure $text): self
    {
        $inner = $this->resolveContent($text);
        $this->parts[] = match ($this->mode) {
            ParseMode::HTML => '<u>' . $inner . '</u>',
            ParseMode::MARKDOWN_V2 => '__' . $inner . '__',
            ParseMode::MARKDOWN => $inner, // Not natively supported in legacy markdown
        };
        return $this;
    }

    /**
     * Appends strikethrough text (supports nested styles).
     */
    public function strikethrough(string|self|Closure $text): self
    {
        $inner = $this->resolveContent($text);
        $this->parts[] = match ($this->mode) {
            ParseMode::HTML => '<s>' . $inner . '</s>',
            ParseMode::MARKDOWN_V2 => '~' . $inner . '~',
            ParseMode::MARKDOWN => $inner,
        };
        return $this;
    }

    /**
     * Appends spoiler text (supports nested styles).
     */
    public function spoiler(string|self|Closure $text): self
    {
        $inner = $this->resolveContent($text);
        $this->parts[] = match ($this->mode) {
            ParseMode::HTML => '<tg-spoiler>' . $inner . '</tg-spoiler>',
            ParseMode::MARKDOWN_V2 => '||' . $inner . '||',
            ParseMode::MARKDOWN => $inner,
        };
        return $this;
    }

    /**
     * Appends a block quotation (single or multi-line, supports nested styles).
     */
    public function blockquote(string|self|Closure $text, bool $expandable = false): self
    {
        $inner = $this->resolveContent($text);
        if ($this->mode === ParseMode::HTML) {
            $tag = $expandable ? '<blockquote expandable>' : '<blockquote>';
            $this->parts[] = $tag . $inner . '</blockquote>';
            return $this;
        }

        // MarkdownV2 blockquote requires '>' before every line
        $lines = explode("\n", $inner);
        $prefix = $expandable ? '**>' : '>';
        $formattedLines = [];

        foreach ($lines as $i => $line) {
            $formattedLines[] = ($i === 0 ? $prefix : '>') . $line;
        }

        $this->parts[] = implode("\n", $formattedLines);
        return $this;
    }

    /**
     * Appends an expandable block quotation.
     */
    public function expandableBlockquote(string|self|Closure $text): self
    {
        return $this->blockquote($text, expandable: true);
    }

    /**
     * Appends inline fixed-width code.
     */
    public function code(string $code): self
    {
        $this->parts[] = match ($this->mode) {
            ParseMode::HTML => '<code>' . Escape::html($code) . '</code>',
            ParseMode::MARKDOWN_V2 => '`' . Escape::markdownV2Code($code) . '`',
            ParseMode::MARKDOWN => '`' . Escape::markdownCode($code) . '`',
        };
        return $this;
    }

    /**
     * Appends a pre-formatted code block with optional syntax highlighting language.
     */
    public function pre(string $code, ?string $language = null): self
    {
        if ($this->mode === ParseMode::HTML) {
            if ($language !== null && $language !== '') {
                $lang = Escape::html($language);
                $this->parts[] = "<pre><code class=\"language-{$lang}\">" . Escape::html($code) . '</code></pre>';
            } else {
                $this->parts[] = '<pre>' . Escape::html($code) . '</pre>';
            }
            return $this;
        }

        // Markdown / MarkdownV2
        $langHeader = $language ?? '';
        $escapedCode = $this->mode === ParseMode::MARKDOWN_V2
            ? Escape::markdownV2Code($code)
            : Escape::markdownCode($code);

        $this->parts[] = "```{$langHeader}\n{$escapedCode}\n```";
        return $this;
    }

    /**
     * Appends an inline URL link (supports nested styled labels).
     */
    public function link(string|self|Closure $text, string $url): self
    {
        $inner = $this->resolveContent($text);

        if ($this->mode === ParseMode::HTML) {
            $this->parts[] = '<a href="' . Escape::html($url) . '">' . $inner . '</a>';
            return $this;
        }

        $escapedUrl = $this->mode === ParseMode::MARKDOWN_V2
            ? Escape::markdownV2Link($url)
            : Escape::markdownLink($url);

        $this->parts[] = "[{$inner}]({$escapedUrl})";
        return $this;
    }

    /**
     * Appends an inline mention of a user by their user ID.
     */
    public function userMention(string|self|Closure $text, int $userId): self
    {
        return $this->link($text, "tg://user?id={$userId}");
    }

    /**
     * Appends a custom Telegram emoji.
     */
    public function customEmoji(string $fallbackEmoji, string $emojiId): self
    {
        if ($this->mode === ParseMode::HTML) {
            $escapedId = Escape::html($emojiId);
            $escapedEmoji = Escape::html($fallbackEmoji);
            $this->parts[] = "<tg-emoji emoji-id=\"{$escapedId}\">{$escapedEmoji}</tg-emoji>";
            return $this;
        }

        $escapedEmoji = Escape::markdownV2CustomEmoji($fallbackEmoji);
        $this->parts[] = "![{$escapedEmoji}](tg://emoji?id={$emojiId})";
        return $this;
    }

    /**
     * Appends a username mention (@username).
     */
    public function mention(string $username): self
    {
        $clean = ltrim($username, '@');
        return $this->plain('@' . $clean);
    }

    /**
     * Appends a hashtag (#topic).
     */
    public function hashtag(string $tag): self
    {
        $clean = ltrim($tag, '#');
        return $this->plain('#' . $clean);
    }

    /**
     * Appends a cashtag ($USD).
     */
    public function cashtag(string $currency): self
    {
        $clean = ltrim($currency, '$');
        return $this->plain('$' . $clean);
    }

    /**
     * Appends a bot command (/start).
     */
    public function botCommand(string $command): self
    {
        $clean = '/' . ltrim($command, '/');
        return $this->plain($clean);
    }

    /**
     * Appends an email address (as mailto: link or plain text).
     */
    public function email(string $email, string|self|Closure|null $label = null): self
    {
        return $this->link($label ?? $email, 'mailto:' . $email);
    }

    /**
     * Appends a phone number (as tel: link or plain text).
     */
    public function phone(string $phone, string|self|Closure|null $label = null): self
    {
        return $this->link($label ?? $phone, 'tel:' . $phone);
    }

    /**
     * Appends a new line, with optional text preceding it.
     */
    public function line(string $text = ''): self
    {
        if ($text !== '') {
            $this->plain($text);
        }
        $this->parts[] = "\n";
        return $this;
    }

    /**
     * Appends one or more new line breaks.
     */
    public function br(int $count = 1): self
    {
        for ($i = 0; $i < $count; $i++) {
            $this->parts[] = "\n";
        }
        return $this;
    }

    /**
     * Appends a horizontal divider line.
     */
    public function hr(int $length = 20, string $char = '—'): self
    {
        return $this->line(str_repeat($char, max(1, $length)));
    }

    /**
     * Appends multiple lines of text.
     */
    public function lines(string ...$lines): self
    {
        foreach ($lines as $line) {
            $this->line($line);
        }
        return $this;
    }

    /**
     * Appends a single space or multiple spaces.
     */
    public function space(int $count = 1): self
    {
        $this->parts[] = str_repeat(' ', max(1, $count));
        return $this;
    }

    /**
     * Calculates the UTF-8 character length of the rendered text.
     */
    public function length(): int
    {
        return mb_strlen($this->__toString());
    }

    /**
     * Returns true if no content has been appended.
     */
    public function isEmpty(): bool
    {
        return empty($this->parts) || $this->length() === 0;
    }

    /**
     * Returns true if content has been appended.
     */
    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * Checks if the rendered text is within Telegram's character limits (default: 4096 characters).
     */
    public function isWithinLimit(int $max = 4096): bool
    {
        return $this->length() <= $max;
    }

    /**
     * Truncates the message to a maximum character limit if exceeded.
     */
    public function truncate(int $maxLength, string $suffix = '...'): self
    {
        $current = $this->__toString();
        if (mb_strlen($current) > $maxLength) {
            $cutLength = max(0, $maxLength - mb_strlen($suffix));
            $this->parts = [mb_substr($current, 0, $cutLength) . $suffix];
        }
        return $this;
    }

    /**
     * Clears all appended content.
     */
    public function clear(): self
    {
        $this->parts = [];
        return $this;
    }

    public function parseMode(): ParseMode
    {
        return $this->mode;
    }

    public function toHtml(): string
    {
        return $this->__toString();
    }

    public function toMarkdownV2(): string
    {
        return $this->__toString();
    }

    public function __toString(): string
    {
        return implode('', $this->parts);
    }
}
