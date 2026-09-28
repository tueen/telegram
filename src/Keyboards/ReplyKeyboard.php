<?php

declare(strict_types=1);

namespace Tueen\Telegram\Keyboards;

use Tueen\Telegram\Types\KeyboardButton;
use Tueen\Telegram\Types\KeyboardButtonPollType;
use Tueen\Telegram\Types\KeyboardButtonRequestChat;
use Tueen\Telegram\Types\KeyboardButtonRequestUsers;
use Tueen\Telegram\Types\ReplyKeyboardMarkup;
use Tueen\Telegram\Types\ReplyKeyboardRemove;
use Tueen\Telegram\Types\WebAppInfo;

/**
 * Fluent builder for Telegram ReplyKeyboardMarkup and ReplyKeyboardRemove.
 *
 * Example:
 * $keyboard = ReplyKeyboard::make()
 *     ->resize()
 *     ->row()
 *         ->text('Send Phone', requestContact: true)
 *         ->text('Send Location', requestLocation: true)
 *     ->row()
 *         ->text('Cancel')
 *     ->build();
 */
final class ReplyKeyboard
{
    /** @var list<list<KeyboardButton>> */
    private array $rows = [];

    private int $currentRow = 0;
    private bool $resizeKeyboard = true;
    private bool $oneTimeKeyboard = false;
    private bool $isPersistent = false;
    private ?bool $selective = null;
    private ?string $inputFieldPlaceholder = null;

    #[\NoDiscard]
    public static function make(): self
    {
        return new self();
    }

    /**
     * Helper to create a ReplyKeyboardRemove object.
     */
    #[\NoDiscard]
    public static function remove(bool $selective = false): ReplyKeyboardRemove
    {
        return new ReplyKeyboardRemove([
            'remove_keyboard' => true,
            'selective' => $selective,
        ]);
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
     * Adds an arbitrary KeyboardButton to the current row.
     */
    public function addButton(KeyboardButton $button): self
    {
        $this->rows[$this->currentRow][] = $button;
        return $this;
    }

    /**
     * Adds a plain text button to the current row.
     */
    public function text(
        string $text,
        bool $requestContact = false,
        bool $requestLocation = false
    ): self {
        $data = ['text' => $text];
        if ($requestContact) {
            $data['request_contact'] = true;
        }
        if ($requestLocation) {
            $data['request_location'] = true;
        }
        return $this->addButton(new KeyboardButton($data));
    }

    /**
     * Adds a button that requests the user's contact information (phone number).
     */
    public function requestContact(string $text): self
    {
        return $this->text($text, requestContact: true);
    }

    /**
     * Adds a button that requests the user's current geo-location.
     */
    public function requestLocation(string $text): self
    {
        return $this->text($text, requestLocation: true);
    }

    /**
     * Adds a button that requests a poll creation from the user.
     */
    public function requestPoll(string $text, ?string $type = null): self
    {
        $data = ['text' => $text];
        if ($type !== null) {
            $data['request_poll'] = ['type' => $type];
        }
        return $this->addButton(new KeyboardButton($data));
    }

    /**
     * Adds a button that requests the user to pick one or more users.
     */
    public function requestUsers(
        string $text,
        int $requestId,
        ?bool $userIsBot = null,
        ?bool $userIsPremium = null,
        ?int $maxQuantity = null
    ): self {
        $req = ['request_id' => $requestId];
        if ($userIsBot !== null) {
            $req['user_is_bot'] = $userIsBot;
        }
        if ($userIsPremium !== null) {
            $req['user_is_premium'] = $userIsPremium;
        }
        if ($maxQuantity !== null) {
            $req['max_quantity'] = $maxQuantity;
        }

        return $this->addButton(new KeyboardButton([
            'text' => $text,
            'request_users' => $req,
        ]));
    }

    /**
     * Adds a button that requests the user to pick a chat/channel.
     */
    public function requestChat(string $text, int $requestId, bool $chatIsChannel = false): self
    {
        return $this->addButton(new KeyboardButton([
            'text' => $text,
            'request_chat' => [
                'request_id' => $requestId,
                'chat_is_channel' => $chatIsChannel,
            ],
        ]));
    }

    /**
     * Adds a Web App button.
     */
    public function webApp(string $text, string $url): self
    {
        return $this->addButton(new KeyboardButton([
            'text' => $text,
            'web_app' => ['url' => $url],
        ]));
    }

    /**
     * Requests clients to resize the keyboard vertically for optimal fit.
     */
    public function resize(bool $resize = true): self
    {
        $this->resizeKeyboard = $resize;
        return $this;
    }

    /**
     * Requests clients to hide the keyboard as soon as it's been used.
     */
    public function oneTime(bool $oneTime = true): self
    {
        $this->oneTimeKeyboard = $oneTime;
        return $this;
    }

    /**
     * Requests clients to always show the keyboard when the regular keyboard is hidden.
     */
    public function persistent(bool $persistent = true): self
    {
        $this->isPersistent = $persistent;
        return $this;
    }

    /**
     * Show keyboard to specific users only (e.g. in groups).
     */
    public function selective(bool $selective = true): self
    {
        $this->selective = $selective;
        return $this;
    }

    /**
     * The placeholder to be shown in the input field when the keyboard is active.
     */
    public function placeholder(string $placeholder): self
    {
        $this->inputFieldPlaceholder = $placeholder;
        return $this;
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
     * Compiles the keyboard into a ReplyKeyboardMarkup Type.
     */
    #[\NoDiscard]
    public function build(): ReplyKeyboardMarkup
    {
        $rawRows = [];
        foreach ($this->rows as $row) {
            if (!empty($row)) {
                $rawRows[] = array_map(fn(KeyboardButton $btn) => $btn->toArray(), $row);
            }
        }

        return new ReplyKeyboardMarkup([
            'keyboard' => $rawRows,
            'resize_keyboard' => $this->resizeKeyboard,
            'one_time_keyboard' => $this->oneTimeKeyboard,
            'is_persistent' => $this->isPersistent,
            'selective' => $this->selective,
            'input_field_placeholder' => $this->inputFieldPlaceholder,
        ]);
    }

    /**
     * Returns the array representation for API requests.
     */
    public function toArray(): array
    {
        return $this->build()->toArray();
    }
}
