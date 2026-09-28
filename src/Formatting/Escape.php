<?php

declare(strict_types=1);

namespace Tueen\Telegram\Formatting;

/**
 * Escaping utilities strictly conforming to Telegram Bot API formatting rules.
 *
 * @link https://core.telegram.org/bots/api#formatting-options
 */
final class Escape
{
    /**
     * Escapes text for HTML mode.
     * All '<', '>', '&', and '"' must be replaced with their respective HTML entities.
     */
    public static function html(string $text): string
    {
        return str_replace(['&', '<', '>', '"'], ['&amp;', '&lt;', '&gt;', '&quot;'], $text);
    }

    /**
     * Escapes special characters for MarkdownV2 mode.
     *
     * Characters that must be escaped:
     * '_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'
     */
    public static function markdownV2(string $text): string
    {
        return preg_replace('/([_\*\[\]\(\)~`>#+\-=|{}.!\\\\])/u', '\\\\$1', $text) ?? $text;
    }

    /**
     * Escapes content inside pre and code entities in MarkdownV2.
     * In pre and code blocks, only '`' and '\' must be escaped.
     */
    public static function markdownV2Code(string $code): string
    {
        return preg_replace('/([`\\\\])/u', '\\\\$1', $code) ?? $code;
    }

    /**
     * Escapes content inside the (...) part of an inline link in MarkdownV2.
     * Inside (...) of an inline link, only ')' and '\' must be escaped.
     */
    public static function markdownV2Link(string $url): string
    {
        return preg_replace('/([\)\\\\])/u', '\\\\$1', $url) ?? $url;
    }

    /**
     * Escapes content inside the [...] part of a custom emoji mention in MarkdownV2.
     * Inside [...] of a custom emoji mention, only ']' and '\' must be escaped.
     */
    public static function markdownV2CustomEmoji(string $emoji): string
    {
        return preg_replace('/([\]\\\\])/u', '\\\\$1', $emoji) ?? $emoji;
    }

    /**
     * Escapes text for legacy Markdown mode.
     * Characters that must be escaped: '_', '*', '`', '['
     */
    public static function markdown(string $text): string
    {
        return preg_replace('/([_\*`\[\\\\])/u', '\\\\$1', $text) ?? $text;
    }

    /**
     * Escapes content inside pre and code entities in legacy Markdown.
     */
    public static function markdownCode(string $code): string
    {
        return preg_replace('/([`\\\\])/u', '\\\\$1', $code) ?? $code;
    }

    /**
     * Escapes content inside the (...) part of an inline link in legacy Markdown.
     */
    public static function markdownLink(string $url): string
    {
        return preg_replace('/([\)\\\\])/u', '\\\\$1', $url) ?? $url;
    }
}
