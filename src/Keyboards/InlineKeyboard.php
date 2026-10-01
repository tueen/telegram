<?php

declare(strict_types=1);

namespace Tueen\Telegram\Keyboards;

use Tueen\Telegram\Types\InlineKeyboardButton;
use Tueen\Telegram\Types\InlineKeyboardMarkup;
use Tueen\Telegram\Types\LoginUrl;
use Tueen\Telegram\Types\SwitchInlineQueryChosenChat;

/**
 * Fluent builder for Telegram InlineKeyboardMarkup.
 *
 * Example:
 * $keyboard = InlineKeyboard::make()
 *     ->row()
 *         ->callback('Option A', 'opt_a')
 *         ->callback('Option B', 'opt_b')
 *     ->row()
 *         ->url('Official Website', 'https://tueen.org')
 *     ->build();
 */
class InlineKeyboard extends InlineKeyboardMarkup
{
    /** @var list<list<InlineKeyboardButton>> */
    private array $rows = [];

    private int $currentRow = 0;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    #[\NoDiscard]
    public static function make(): self
    {
        return new self();
    }

    /**
     * Starts a new row of buttons.
     */
    public function row(): self
    {
        if (!empty($this->rows) && !empty($this->rows[$this->currentRow])) {
            $this->currentRow++;
        }
        return $this;
    }

    /**
     * Adds an arbitrary InlineKeyboardButton to the current row.
     */
    public function addButton(InlineKeyboardButton $button): self
    {
        $this->rows[$this->currentRow][] = $button;
        return $this;
    }

    /**
     * Adds a callback data button to the current row.
     */
    public function callback(string $text, string $callbackData): self
    {
        return $this->addButton(new InlineKeyboardButton([
            'text' => $text,
            'callback_data' => $callbackData,
        ]));
    }

    /**
     * Adds an action button (shorthand alias for {@see callback()}).
     *
     * @param string $text Button label text
     * @param string $action Callback action data string
     * @return self
     * @see callback()
     */
    public function action(string $text, string $action): self
    {
        return $this->callback($text, $action);
    }

    /**
     * Adds an HTTP/HTTPS URL button to the current row.
     */
    public function url(string $text, string $url): self
    {
        return $this->addButton(new InlineKeyboardButton([
            'text' => $text,
            'url' => $url,
        ]));
    }

    /**
     * Adds a Telegram Web App button to the current row.
     */
    public function webApp(string $text, string $url): self
    {
        return $this->addButton(new InlineKeyboardButton([
            'text' => $text,
            'web_app' => ['url' => $url],
        ]));
    }

    /**
     * Adds a Telegram Login URL button to the current row.
     */
    public function loginUrl(string $text, string|LoginUrl $url): self
    {
        $loginUrl = is_string($url) ? ['url' => $url] : $url->toArray();
        return $this->addButton(new InlineKeyboardButton([
            'text' => $text,
            'login_url' => $loginUrl,
        ]));
    }

    /**
     * Prompts the user to select one of their chats to insert the bot's username and the specified inline query.
     */
    public function switchInlineQuery(string $text, string $query = ''): self
    {
        return $this->addButton(new InlineKeyboardButton([
            'text' => $text,
            'switch_inline_query' => $query,
        ]));
    }

    /**
     * Inserts the bot's username and the specified inline query in the current chat's input field.
     */
    public function switchInlineQueryCurrentChat(string $text, string $query = ''): self
    {
        return $this->addButton(new InlineKeyboardButton([
            'text' => $text,
            'switch_inline_query_current_chat' => $query,
        ]));
    }

    /**
     * Prompts the user to select one of their chats of the specified type.
     */
    public function switchInlineQueryChosenChat(string $text, SwitchInlineQueryChosenChat $chosenChat): self
    {
        return $this->addButton(new InlineKeyboardButton([
            'text' => $text,
            'switch_inline_query_chosen_chat' => $chosenChat->toArray(),
        ]));
    }

    /**
     * Adds a button that copies specified text to clipboard when pressed.
     */
    public function copyText(string $text, string $textToCopy): self
    {
        return $this->addButton(new InlineKeyboardButton([
            'text' => $text,
            'copy_text' => ['text' => $textToCopy],
        ]));
    }

    /**
     * Adds a Pay button for Telegram Payments.
     */
    public function pay(string $text): self
    {
        return $this->addButton(new InlineKeyboardButton([
            'text' => $text,
            'pay' => true,
        ]));
    }

    /**
     * Adds a button with flexible configuration.
     */
    public function button(
        string $text,
        ?string $callbackData = null,
        ?string $url = null,
        ?string $webAppUrl = null
    ): self {
        if ($callbackData !== null) {
            return $this->callback($text, $callbackData);
        }

        if ($url !== null) {
            return $this->url($text, $url);
        }

        if ($webAppUrl !== null) {
            return $this->webApp($text, $webAppUrl);
        }

        return $this->callback($text, $text);
    }

    /**
     * Automatically chunks all flattened buttons into rows of the given size.
     */
    public function chunk(int $size): self
    {
        if ($size <= 0) {
            return $this;
        }

        $allButtons = [];
        foreach ($this->rows as $row) {
            foreach ($row as $btn) {
                $allButtons[] = $btn;
            }
        }

        $this->rows = array_chunk($allButtons, $size);
        $this->currentRow = max(0, count($this->rows) - 1);
        return $this;
    }

    /**
     * Compiles the keyboard into an InlineKeyboardMarkup Type.
     */
    #[\NoDiscard]
    public function build(): InlineKeyboardMarkup
    {
        $rawRows = [];
        foreach ($this->rows as $row) {
            if (!empty($row)) {
                $rawRows[] = array_map(fn(InlineKeyboardButton $btn) => $btn->toArray(), $row);
            }
        }

        return new InlineKeyboardMarkup(['inline_keyboard' => $rawRows]);
    }

    /**
     * Returns the array representation for API requests.
     */
    #[\Override]
    public function toArray(): array
    {
        if (!empty($this->rows)) {
            return $this->build()->toArray();
        }
        return parent::toArray();
    }

    #[\Override]
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
