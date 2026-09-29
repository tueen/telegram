<?php

declare(strict_types=1);

namespace Tueen\Telegram\Flow;

use Tueen\Telegram\Types\InlineKeyboardButton;
use Tueen\Telegram\Types\KeyboardButton;

/**
 * Navigation bar configuration for InteractiveFlow screens.
 *
 * Provides standardized navigation buttons with language-neutral emoji defaults:
 * - Back button: 🔙
 * - Home button: 🏠
 */
final class Navigation
{
    public const DEFAULT_BACK_LABEL = '🔙';
    public const DEFAULT_HOME_LABEL = '🏠';

    public const BACK_ACTION = 'flow:back';
    public const HOME_ACTION = 'flow:home';

    public function __construct(
        public bool $back = false,
        public bool $home = false,
        public string $backLabel = self::DEFAULT_BACK_LABEL,
        public string $homeLabel = self::DEFAULT_HOME_LABEL,
        public string $backAction = self::BACK_ACTION,
        public string $homeAction = self::HOME_ACTION,
    ) {}

    #[\NoDiscard]
    public static function make(): self
    {
        return new self();
    }

    public function withBack(bool $enabled = true, ?string $label = null): self
    {
        $this->back = $enabled;
        if ($label !== null) {
            $this->backLabel = $label;
        }
        return $this;
    }

    public function withHome(bool $enabled = true, ?string $label = null): self
    {
        $this->home = $enabled;
        if ($label !== null) {
            $this->homeLabel = $label;
        }
        return $this;
    }

    public function backLabel(string $label): self
    {
        $this->backLabel = $label;
        return $this;
    }

    public function homeLabel(string $label): self
    {
        $this->homeLabel = $label;
        return $this;
    }

    public function hasButtons(): bool
    {
        return $this->back || $this->home;
    }

    /**
     * @return list<InlineKeyboardButton>
     */
    public function toInlineButtons(): array
    {
        $buttons = [];

        if ($this->back) {
            $buttons[] = new InlineKeyboardButton([
                'text' => $this->backLabel,
                'callback_data' => $this->backAction,
            ]);
        }

        if ($this->home) {
            $buttons[] = new InlineKeyboardButton([
                'text' => $this->homeLabel,
                'callback_data' => $this->homeAction,
            ]);
        }

        return $buttons;
    }

    /**
     * @return list<KeyboardButton>
     */
    public function toReplyButtons(): array
    {
        $buttons = [];

        if ($this->back) {
            $buttons[] = new KeyboardButton([
                'text' => $this->backLabel,
            ]);
        }

        if ($this->home) {
            $buttons[] = new KeyboardButton([
                'text' => $this->homeLabel,
            ]);
        }

        return $buttons;
    }
}
