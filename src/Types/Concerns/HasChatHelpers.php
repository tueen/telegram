<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types\Concerns;

trait HasChatHelpers
{
    /**
     * Display name of the chat (title for groups/channels, or first_name + last_name for private chats).
     */
    public ?string $fullName {
        get => $this->getFullName();
    }

    /**
     * Resolves the full display name of the chat.
     */
    public function getFullName(): ?string
    {
        if (!empty($this->title)) {
            return $this->title;
        }

        $parts = array_filter(
            [$this->firstName ?? null, $this->lastName ?? null],
            fn(?string $val) => $val !== null && trim($val) !== ''
        );

        return !empty($parts) ? implode(' ', $parts) : null;
    }
}
