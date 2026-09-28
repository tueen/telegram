<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types\Custom;

use ArrayIterator;
use Countable;
use Traversable;
use Tueen\Telegram\Types\Type;

/**
 * Encapsulates array API responses from Telegram (e.g. getUpdates, sendMediaGroup)
 * into a rich, collection-like Type object.
 *
 * @template T
 */
class ArrayResult extends Type implements Countable
{
    /**
     * @var array<int|string, mixed>
     */
    private(set) array $items = [];

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

    /**
     * @return array<int|string, mixed>
     */
    #[\NoDiscard]
    public function all(): array
    {
        return $this->items;
    }

    /**
     * @return mixed
     */
    #[\NoDiscard]
    public function first(): mixed
    {
        return array_first($this->items);
    }

    /**
     * @return mixed
     */
    #[\NoDiscard]
    public function last(): mixed
    {
        return array_last($this->items);
    }

    public function get(int|string $key, mixed $default = null): mixed
    {
        return $this->items[$key] ?? $default;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function isNotEmpty(): bool
    {
        return !empty($this->items);
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

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    public function jsonSerialize(): array
    {
        return array_map(function ($item) {
            if ($item instanceof Type) {
                return $item->jsonSerialize();
            }
            return $item;
        }, $this->items);
    }

    public function toArray(): array
    {
        return $this->jsonSerialize();
    }

    public function __toString(): string
    {
        return json_encode($this->jsonSerialize(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?: '[]';
    }
}
