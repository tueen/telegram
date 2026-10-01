<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types\Custom;

use ArrayIterator;
use Tueen\Telegram\Types\Type;

/**
 * Encapsulates array API responses from Telegram (e.g. getUpdates, sendMediaGroup)
 * into a rich, collection-like Type object.
 *
 * @template T
 */
class ArrayResult extends Type
{
    /**
     * @var array<int|string, mixed>
     */
    private(set) array $items = [];

    /**
     * First element of the items collection, or null if empty.
     */
    public mixed $first {
        get => array_first($this->items);
    }

    /**
     * Last element of the items collection, or null if empty.
     */
    public mixed $last {
        get => array_last($this->items);
    }

    /**
     * Number of items in the collection.
     */
    public int $count {
        get => count($this->items);
    }

    /**
     * Whether the collection contains no items.
     */
    public bool $isEmpty {
        get => empty($this->items);
    }

    /**
     * Whether the collection contains at least one item.
     */
    public bool $isNotEmpty {
        get => !empty($this->items);
    }

    /**
     * @param array<int|string, mixed> $items
     */
    public function __construct(array $items = [])
    {
        if (isset($items['items']) && is_array($items['items'])) {
            $this->items = $items['items'];
            parent::__construct($items);
        } else {
            $this->items = array_values($items);
            parent::__construct(['items' => $this->items]);
        }
    }

    public function get(int|string $key, mixed $default = null): mixed
    {
        return $this->items[$key] ?? $default;
    }

    /**
     * Maps elements to a new ArrayResult instance.
     */
    public function map(callable $callback): static
    {
        return new static(array_map($callback, $this->items));
    }

    /**
     * Filters elements using a callback.
     */
    public function filter(?callable $callback = null): static
    {
        if ($callback === null) {
            return new static(array_values(array_filter($this->items)));
        }

        return new static(array_values(array_filter($this->items, $callback)));
    }

    /**
     * Extracts values of a specific property or key from all items.
     */
    public function pluck(string $key): array
    {
        $values = [];
        foreach ($this->items as $item) {
            if (is_object($item)) {
                $values[] = $item->{$key} ?? null;
            } elseif (is_array($item)) {
                $values[] = $item[$key] ?? null;
            }
        }
        return $values;
    }

    #[\Override]
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    #[\Override]
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    #[\Override]
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    #[\Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    #[\Override]
    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return array_map(function ($item) {
            if ($item instanceof Type) {
                return $item->jsonSerialize();
            }
            return $item;
        }, $this->items);
    }

    #[\Override]
    public function toArray(): array
    {
        return $this->jsonSerialize();
    }

    #[\Override]
    public function __toString(): string
    {
        return json_encode($this->jsonSerialize(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?: '[]';
    }
}
