<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types\Concerns;

use Tueen\Telegram\Types\InlineKeyboardButton;

/**
 * Helper methods and transformation utilities for InlineKeyboardMarkup.
 */
trait HasInlineKeyboardHelpers
{
    /**
     * Total number of rows in the keyboard.
     */
    public int $rowCount {
        get => isset($this->inlineKeyboard) ? count($this->inlineKeyboard) : 0;
    }

    /**
     * Total number of buttons across all rows in the keyboard.
     */
    public int $buttonCount {
        get => isset($this->inlineKeyboard)
            ? array_sum(array_map('count', $this->inlineKeyboard))
            : 0;
    }

    /**
     * Whether the keyboard contains zero buttons or rows.
     */
    public bool $isEmpty {
        get => !isset($this->inlineKeyboard) || empty($this->inlineKeyboard);
    }

    /**
     * Appends a new row of buttons to the end of the keyboard.
     */
    public function addRow(InlineKeyboardButton ...$buttons): static
    {
        if (!isset($this->inlineKeyboard)) {
            $this->inlineKeyboard = [];
        }

        $this->inlineKeyboard[] = array_values($buttons);

        return $this;
    }

    /**
     * Prepends a new row of buttons to the beginning of the keyboard.
     */
    public function prependRow(InlineKeyboardButton ...$buttons): static
    {
        if (!isset($this->inlineKeyboard)) {
            $this->inlineKeyboard = [];
        }

        array_unshift($this->inlineKeyboard, array_values($buttons));

        return $this;
    }

    /**
     * Inserts a row of buttons at a specific row index.
     */
    public function insertRow(int $index, InlineKeyboardButton ...$buttons): static
    {
        if (!isset($this->inlineKeyboard)) {
            $this->inlineKeyboard = [];
        }

        if ($index < 0) {
            $index = max(0, count($this->inlineKeyboard) + $index);
        }

        array_splice($this->inlineKeyboard, $index, 0, [array_values($buttons)]);

        return $this;
    }

    /**
     * Removes a row of buttons at a specific index.
     */
    public function removeRow(int $index): static
    {
        if (!isset($this->inlineKeyboard)) {
            return $this;
        }

        if ($index < 0) {
            $index = count($this->inlineKeyboard) + $index;
        }

        if (isset($this->inlineKeyboard[$index])) {
            array_splice($this->inlineKeyboard, $index, 1);
        }

        return $this;
    }

    /**
     * Reverses the vertical order of rows (bottom row becomes first row, etc.).
     */
    public function reverseRows(): static
    {
        if (isset($this->inlineKeyboard)) {
            $this->inlineKeyboard = array_reverse($this->inlineKeyboard);
        }

        return $this;
    }

    /**
     * Reverses the horizontal order of buttons within a specific row.
     * Useful for adjusting navigation buttons in right-to-left (RTL) languages.
     * Supports negative indices (-1 indicates the last row).
     */
    public function reverseRow(int $index): static
    {
        if (!isset($this->inlineKeyboard)) {
            return $this;
        }

        if ($index < 0) {
            $index = count($this->inlineKeyboard) + $index;
        }

        if (isset($this->inlineKeyboard[$index])) {
            $this->inlineKeyboard[$index] = array_reverse($this->inlineKeyboard[$index]);
        }

        return $this;
    }

    /**
     * Reverses the horizontal order of buttons across all rows.
     * Essential for adapting keyboards to right-to-left (RTL) languages like Persian or Arabic.
     */
    public function reverseColumns(): static
    {
        if (isset($this->inlineKeyboard)) {
            $this->inlineKeyboard = array_map('array_reverse', $this->inlineKeyboard);
        }

        return $this;
    }

    /**
     * Convenient alias for reverseColumns() specifically for RTL languages.
     */
    public function rtl(): static
    {
        return $this->reverseColumns();
    }

    /**
     * Filters buttons across all rows using a callback predicate.
     * Empty rows can be automatically pruned if $pruneEmptyRows is true.
     *
     * @param callable(InlineKeyboardButton): bool $predicate
     */
    public function filterButtons(callable $predicate, bool $pruneEmptyRows = true): static
    {
        if (!isset($this->inlineKeyboard)) {
            return $this;
        }

        $filteredRows = [];
        foreach ($this->inlineKeyboard as $row) {
            $filteredRow = array_values(array_filter($row, $predicate));
            if (!$pruneEmptyRows || !empty($filteredRow)) {
                $filteredRows[] = $filteredRow;
            }
        }

        $this->inlineKeyboard = $filteredRows;

        return $this;
    }

    /**
     * Applies a mapping callback to each button across all rows.
     *
     * @param callable(InlineKeyboardButton): InlineKeyboardButton $callback
     */
    public function mapButtons(callable $callback): static
    {
        if (!isset($this->inlineKeyboard)) {
            return $this;
        }

        $mappedRows = [];
        foreach ($this->inlineKeyboard as $row) {
            $mappedRows[] = array_map($callback, $row);
        }

        $this->inlineKeyboard = $mappedRows;

        return $this;
    }
}
