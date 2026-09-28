<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types\Concerns;

use Tueen\Telegram\Types\KeyboardButton;

/**
 * Helper methods and transformation utilities for ReplyKeyboardMarkup.
 */
trait HasReplyKeyboardHelpers
{
    /**
     * Total number of rows in the keyboard.
     */
    public int $rowCount {
        get => isset($this->keyboard) ? count($this->keyboard) : 0;
    }

    /**
     * Total number of buttons across all rows in the keyboard.
     */
    public int $buttonCount {
        get => isset($this->keyboard)
            ? array_sum(array_map('count', $this->keyboard))
            : 0;
    }

    /**
     * Whether the keyboard contains zero buttons or rows.
     */
    public bool $isEmpty {
        get => !isset($this->keyboard) || empty($this->keyboard);
    }

    /**
     * Appends a new row of buttons to the end of the keyboard.
     */
    public function addRow(KeyboardButton ...$buttons): static
    {
        if (!isset($this->keyboard)) {
            $this->keyboard = [];
        }

        $this->keyboard[] = array_values($buttons);

        return $this;
    }

    /**
     * Prepends a new row of buttons to the beginning of the keyboard.
     */
    public function prependRow(KeyboardButton ...$buttons): static
    {
        if (!isset($this->keyboard)) {
            $this->keyboard = [];
        }

        array_unshift($this->keyboard, array_values($buttons));

        return $this;
    }

    /**
     * Inserts a row of buttons at a specific row index.
     */
    public function insertRow(int $index, KeyboardButton ...$buttons): static
    {
        if (!isset($this->keyboard)) {
            $this->keyboard = [];
        }

        if ($index < 0) {
            $index = max(0, count($this->keyboard) + $index);
        }

        array_splice($this->keyboard, $index, 0, [array_values($buttons)]);

        return $this;
    }

    /**
     * Removes a row of buttons at a specific index.
     */
    public function removeRow(int $index): static
    {
        if (!isset($this->keyboard)) {
            return $this;
        }

        if ($index < 0) {
            $index = count($this->keyboard) + $index;
        }

        if (isset($this->keyboard[$index])) {
            array_splice($this->keyboard, $index, 1);
        }

        return $this;
    }

    /**
     * Reverses the vertical order of rows (bottom row becomes first row, etc.).
     */
    public function reverseRows(): static
    {
        if (isset($this->keyboard)) {
            $this->keyboard = array_reverse($this->keyboard);
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
        if (!isset($this->keyboard)) {
            return $this;
        }

        if ($index < 0) {
            $index = count($this->keyboard) + $index;
        }

        if (isset($this->keyboard[$index])) {
            $this->keyboard[$index] = array_reverse($this->keyboard[$index]);
        }

        return $this;
    }

    /**
     * Reverses the horizontal order of buttons across all rows.
     * Essential for adapting keyboards to right-to-left (RTL) languages like Persian or Arabic.
     */
    public function reverseColumns(): static
    {
        if (isset($this->keyboard)) {
            $this->keyboard = array_map('array_reverse', $this->keyboard);
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
     * @param callable(KeyboardButton): bool $predicate
     */
    public function filterButtons(callable $predicate, bool $pruneEmptyRows = true): static
    {
        if (!isset($this->keyboard)) {
            return $this;
        }

        $filteredRows = [];
        foreach ($this->keyboard as $row) {
            $filteredRow = array_values(array_filter($row, $predicate));
            if (!$pruneEmptyRows || !empty($filteredRow)) {
                $filteredRows[] = $filteredRow;
            }
        }

        $this->keyboard = $filteredRows;

        return $this;
    }

    /**
     * Applies a mapping callback to each button across all rows.
     *
     * @param callable(KeyboardButton): KeyboardButton $callback
     */
    public function mapButtons(callable $callback): static
    {
        if (!isset($this->keyboard)) {
            return $this;
        }

        $mappedRows = [];
        foreach ($this->keyboard as $row) {
            $mappedRows[] = array_map($callback, $row);
        }

        $this->keyboard = $mappedRows;

        return $this;
    }
}
