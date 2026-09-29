<?php

declare(strict_types=1);

namespace Tueen\Telegram\Flow;

use Closure;
use Tueen\Telegram\Enums\ParseMode;
use Tueen\Telegram\Keyboards\InlineKeyboard;
use Tueen\Telegram\Keyboards\ReplyKeyboard;
use Tueen\Telegram\Types\ForceReply;
use Tueen\Telegram\Types\InlineKeyboardMarkup;
use Tueen\Telegram\Types\ReplyKeyboardMarkup;
use Tueen\Telegram\Types\ReplyKeyboardRemove;

/**
 * Screen encapsulates the visual representation of an InteractiveFlow step.
 *
 * It models the message text, formatting, media, keyboard markup, and navigation bar.
 */
class Screen
{
    private string $text = '';
    private ParseMode|string|null $parseMode = null;
    private mixed $keyboard = null;
    private mixed $media = null;
    private ?string $mediaType = null;
    private mixed $mediaSource = null;
    private ?array $pagination = null;
    private ?array $checklist = null;
    private ?array $stepper = null;
    private bool $enableBreadcrumbs = false;
    private string $breadcrumbsSeparator = ' › ';
    private ?Navigation $navigation = null;
    private bool $editIfPossible = true;

    public function __construct(string $text = '')
    {
        $this->text = $text;
    }

    #[\NoDiscard]
    public static function make(string $text = ''): self
    {
        return new self($text);
    }

    public function text(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    public function parseMode(ParseMode|string|null $parseMode): self
    {
        $this->parseMode = $parseMode;
        return $this;
    }

    /**
     * Attaches an InlineKeyboard to the screen.
     */
    public function inline(callable|InlineKeyboard $keyboard): self
    {
        if (is_callable($keyboard)) {
            $builder = InlineKeyboard::make();
            $keyboard($builder);
            $this->keyboard = $builder;
        } else {
            $this->keyboard = $keyboard;
        }
        return $this;
    }

    /**
     * Attaches a native ReplyKeyboard to the screen.
     */
    public function reply(callable|ReplyKeyboard $keyboard): self
    {
        if (is_callable($keyboard)) {
            $builder = ReplyKeyboard::make();
            $keyboard($builder);
            $this->keyboard = $builder;
        } else {
            $this->keyboard = $keyboard;
        }
        return $this;
    }

    /**
     * Removes the native custom keyboard.
     */
    public function removeKeyboard(bool $selective = false): self
    {
        $this->keyboard = ReplyKeyboard::remove($selective);
        return $this;
    }

    /**
     * Requests a force-reply from the user.
     */
    public function forceReply(bool $selective = false, ?string $inputFieldPlaceholder = null): self
    {
        $params = [
            'force_reply' => true,
            'selective' => $selective,
        ];
        if ($inputFieldPlaceholder !== null) {
            $params['input_field_placeholder'] = $inputFieldPlaceholder;
        }
        $this->keyboard = new ForceReply($params);
        return $this;
    }

    /**
     * Configures the screen's navigation bar.
     */
    public function withNavigation(
        bool $back = true,
        bool $home = false,
        ?string $backLabel = null,
        ?string $homeLabel = null
    ): self {
        $nav = $this->navigation ?? new Navigation();
        $nav->withBack($back, $backLabel);
        $nav->withHome($home, $homeLabel);
        $this->navigation = $nav;
        return $this;
    }

    public function navigation(Navigation $navigation): self
    {
        $this->navigation = $navigation;
        return $this;
    }

    public function media(mixed $media): self
    {
        $this->media = $media;
        return $this;
    }

    public function photo(mixed $photo, ?string $caption = null): self
    {
        $this->mediaType = 'photo';
        $this->mediaSource = $photo;
        if ($caption !== null) {
            $this->text = $caption;
        }
        return $this;
    }

    public function video(mixed $video, ?string $caption = null): self
    {
        $this->mediaType = 'video';
        $this->mediaSource = $video;
        if ($caption !== null) {
            $this->text = $caption;
        }
        return $this;
    }

    public function animation(mixed $animation, ?string $caption = null): self
    {
        $this->mediaType = 'animation';
        $this->mediaSource = $animation;
        if ($caption !== null) {
            $this->text = $caption;
        }
        return $this;
    }

    /**
     * Appends an automatic pagination row to the inline keyboard.
     */
    public function withPagination(
        int $currentPage,
        int $totalPages,
        string $actionPattern = 'page_{page}',
        string $prevLabel = '◀️',
        string $nextLabel = '▶️',
        bool $showIndicator = true
    ): self {
        $this->pagination = [
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'actionPattern' => $actionPattern,
            'prevLabel' => $prevLabel,
            'nextLabel' => $nextLabel,
            'showIndicator' => $showIndicator,
        ];
        return $this;
    }

    /**
     * Appends a multi-select interactive checklist grid to the screen.
     *
     * @param array<string, string> $items Key-value pairs of items (id => label)
     * @param list<string> $selected List of currently selected item keys
     * @param string $toggleActionPattern Callback pattern for toggling an item
     * @param string|null $confirmAction Callback action when pressing Done
     * @param string|null $confirmLabel Custom label for the confirmation button
     * @param int $columns Number of columns for checklist items
     */
    public function withChecklist(
        array $items,
        array $selected,
        string $toggleActionPattern = 'toggle_{key}',
        ?string $confirmAction = null,
        ?string $confirmLabel = null,
        int $columns = 1
    ): self {
        $this->checklist = [
            'items' => $items,
            'selected' => array_map('strval', $selected),
            'toggleActionPattern' => $toggleActionPattern,
            'confirmAction' => $confirmAction,
            'confirmLabel' => $confirmLabel,
            'columns' => max(1, $columns),
        ];
        return $this;
    }

    /**
     * Prepends a step wizard visual indicator to the screen text.
     *
     * @param int $currentStep Active 1-indexed step number
     * @param int $totalSteps Total number of steps
     * @param string $style Visual style: 'dots' (● ● ○ ○), 'bar' ([████░░░░]), or 'numbers' (2/4)
     * @param string|null $format Custom format template
     */
    public function withStepper(
        int $currentStep,
        int $totalSteps,
        string $style = 'dots',
        ?string $format = null
    ): self {
        $this->stepper = [
            'currentStep' => $currentStep,
            'totalSteps' => $totalSteps,
            'style' => $style,
            'format' => $format,
        ];
        return $this;
    }

    /**
     * Enables automatic breadcrumb hierarchy rendering at the top of the screen.
     */
    public function withBreadcrumbs(bool $enabled = true, string $separator = ' › '): self
    {
        $this->enableBreadcrumbs = $enabled;
        $this->breadcrumbsSeparator = $separator;
        return $this;
    }

    public function isBreadcrumbsEnabled(): bool
    {
        return $this->enableBreadcrumbs;
    }

    public function getBreadcrumbsSeparator(): string
    {
        return $this->breadcrumbsSeparator;
    }

    public function editIfPossible(bool $edit = true): self
    {
        $this->editIfPossible = $edit;
        return $this;
    }

    public function getText(): string
    {
        $content = $this->text;

        if ($this->stepper !== null) {
            $cur = $this->stepper['currentStep'];
            $total = $this->stepper['totalSteps'];
            $style = $this->stepper['style'];

            $indicator = match ($style) {
                'bar' => (function () use ($cur, $total) {
                    $ratio = $total > 0 ? min(1.0, max(0.0, $cur / $total)) : 0.0;
                    $filled = (int) round($ratio * 8);
                    return '[' . str_repeat('█', $filled) . str_repeat('░', 8 - $filled) . ']';
                })(),
                'numbers' => "({$cur}/{$total})",
                default => (function () use ($cur, $total) {
                    $dots = [];
                    for ($i = 1; $i <= $total; $i++) {
                        $dots[] = $i <= $cur ? '●' : '○';
                    }
                    return implode(' ', $dots);
                })(),
            };

            $header = $this->stepper['format'] !== null
                ? strtr($this->stepper['format'], ['{indicator}' => $indicator, '{current}' => (string) $cur, '{total}' => (string) $total])
                : "{$indicator} Step {$cur} of {$total}";

            $content = $header . "\n\n" . $content;
        }

        return $content;
    }

    public function getParseMode(): ParseMode|string|null
    {
        return $this->parseMode;
    }

    public function getKeyboard(): mixed
    {
        return $this->keyboard;
    }

    public function getMedia(): mixed
    {
        return $this->media;
    }

    public function getMediaType(): ?string
    {
        return $this->mediaType;
    }

    public function getMediaSource(): mixed
    {
        return $this->mediaSource;
    }

    public function getNavigation(): ?Navigation
    {
        return $this->navigation;
    }

    public function shouldEditIfPossible(): bool
    {
        return $this->editIfPossible;
    }

    /**
     * Compiles the keyboard and attaches navigation, pagination, and checklist buttons if configured.
     */
    public function buildKeyboard(): mixed
    {
        $hasNavButtons = $this->navigation !== null && $this->navigation->hasButtons();
        $hasPagination = $this->pagination !== null && ($this->pagination['totalPages'] > 1);
        $hasChecklist = $this->checklist !== null && !empty($this->checklist['items']);

        // 1. If InlineKeyboard or null keyboard with navigation/pagination/checklist
        if ($this->keyboard instanceof InlineKeyboard || ($this->keyboard === null && ($hasNavButtons || $hasPagination || $hasChecklist))) {
            $inline = $this->keyboard instanceof InlineKeyboard ? clone $this->keyboard : InlineKeyboard::make();

            // Append checklist items
            if ($hasChecklist) {
                $items = $this->checklist['items'];
                $selected = $this->checklist['selected'];
                $pattern = $this->checklist['toggleActionPattern'];
                $cols = $this->checklist['columns'];

                $checklistButtons = [];
                foreach ($items as $key => $label) {
                    $strKey = (string) $key;
                    $isChecked = in_array($strKey, $selected, true);
                    $icon = $isChecked ? '✅ ' : '◻️ ';
                    $action = str_replace('{key}', $strKey, $pattern);
                    $checklistButtons[] = ['text' => $icon . $label, 'callback_data' => $action];
                }

                $chunked = array_chunk($checklistButtons, $cols);
                foreach ($chunked as $rowBtns) {
                    $inline->row();
                    foreach ($rowBtns as $btn) {
                        $inline->action($btn['text'], $btn['callback_data']);
                    }
                }

                if ($this->checklist['confirmAction'] !== null) {
                    $confirmLabel = $this->checklist['confirmLabel'] ?? ('✅ Done (' . count($selected) . ')');
                    $inline->row()->action($confirmLabel, $this->checklist['confirmAction']);
                }
            }

            // Append pagination row
            if ($hasPagination) {
                $p = $this->pagination;
                $cur = $p['currentPage'];
                $total = $p['totalPages'];
                $pattern = $p['actionPattern'];

                $inline->row();
                if ($cur > 1) {
                    $prevAction = str_replace('{page}', (string) ($cur - 1), $pattern);
                    $inline->action($p['prevLabel'], $prevAction);
                }
                if ($p['showIndicator']) {
                    $inline->action("{$cur}/{$total}", 'noop');
                }
                if ($cur < $total) {
                    $nextAction = str_replace('{page}', (string) ($cur + 1), $pattern);
                    $inline->action($p['nextLabel'], $nextAction);
                }
            }

            // Append navigation row
            if ($hasNavButtons) {
                $inline->row();
                foreach ($this->navigation->toInlineButtons() as $btn) {
                    $inline->addButton($btn);
                }
            }

            return $inline->build();
        }

        // 2. If ReplyKeyboard
        if ($this->keyboard instanceof ReplyKeyboard) {
            $reply = clone $this->keyboard;

            if ($hasNavButtons) {
                $reply->row();
                foreach ($this->navigation->toReplyButtons() as $btn) {
                    $reply->addButton($btn);
                }
            }

            return $reply->build();
        }

        return $this->keyboard;
    }
}
