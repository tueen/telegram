<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use ArrayAccess;
use IteratorAggregate;
use ArrayIterator;
use JsonSerializable;
use Stringable;
use ReflectionClass;
use ReflectionProperty;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

class Type implements ArrayAccess, IteratorAggregate, JsonSerializable, Stringable
{
    /**
     * Raw array data provided from Telegram API.
     */
    protected array $raw = [];

    /**
     * Additional dynamic fields not defined on the concrete class.
     */
    protected array $extra = [];

    /**
     * In-memory cache of property metadata per concrete Type class.
     * @var array<class-string, array<string, array<string, mixed>>>
     */
    private static array $classPropertiesCache = [];

    public function __construct(array $data = [])
    {
        $this->raw = $data;
        $this->populate($data);
    }

    /**
     * Resolves and caches reflection property metadata for a Type class.
     *
     * @param class-string $class
     * @return array<string, array<string, mixed>>
     */
    private static function resolveClassProperties(string $class): array
    {
        $reflection = new ReflectionClass($class);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

        $cached = [];

        foreach ($properties as $prop) {
            if ($prop->isStatic() || $prop->isVirtual()) {
                continue;
            }

            $propName = $prop->getName();
            if ($propName === 'raw' || $propName === 'extra') {
                continue;
            }

            // 1. Resolve field name
            $fieldName = self::toSnakeCase($propName);
            $attrs = $prop->getAttributes(Field::class);
            if (!empty($attrs)) {
                $fieldName = $attrs[0]->newInstance()->name;
            }

            // 2. Check #[ArrayOf] attribute
            $arrayOfTarget = null;
            $arrayAttrs = $prop->getAttributes(ArrayOf::class);
            if (!empty($arrayAttrs)) {
                $arrayOfTarget = $arrayAttrs[0]->newInstance()->type;
            }

            // 3. Inspect property types
            $type = $prop->getType();
            $targetTypes = [];
            if ($type instanceof \ReflectionNamedType) {
                $targetTypes[] = $type;
            } elseif ($type instanceof \ReflectionUnionType) {
                $targetTypes = $type->getTypes();
            }

            $targetClass = null;
            $targetEnum = null;
            $isBuiltinArray = false;

            foreach ($targetTypes as $t) {
                if ($t->isBuiltin()) {
                    if ($t->getName() === 'array') {
                        $isBuiltinArray = true;
                    }
                    continue;
                }

                $className = $t->getName();
                if (enum_exists($className)) {
                    $targetEnum = $className;
                } elseif (is_subclass_of($className, Type::class) || $className === Type::class) {
                    $targetClass = $className;
                }
            }

            $cached[$propName] = [
                'prop' => $prop,
                'fieldName' => $fieldName,
                'snakeName' => self::toSnakeCase($propName),
                'arrayOfTarget' => $arrayOfTarget,
                'targetEnum' => $targetEnum,
                'targetClass' => $targetClass,
                'isBuiltinArray' => $isBuiltinArray,
            ];
        }

        return $cached;
    }

    /**
     * Populates class properties and extra storage from input array.
     */
    protected function populate(array $data): void
    {
        $class = static::class;
        $properties = self::$classPropertiesCache[$class] ??= self::resolveClassProperties($class);

        $handledKeys = [];

        foreach ($properties as $propName => $meta) {
            $fieldName = $meta['fieldName'];
            $snake = $meta['snakeName'];

            $val = null;
            if (array_key_exists($fieldName, $data)) {
                $val = $data[$fieldName];
                $handledKeys[$fieldName] = true;
            } elseif (array_key_exists($propName, $data)) {
                $val = $data[$propName];
                $handledKeys[$propName] = true;
            } elseif (array_key_exists($snake, $data)) {
                $val = $data[$snake];
                $handledKeys[$snake] = true;
            }

            if ($val !== null) {
                $castValue = self::castValueWithMeta($val, $meta);
                /** @var ReflectionProperty $prop */
                $prop = $meta['prop'];
                $prop->setValue($this, $castValue);
            }
        }

        // Store any remaining fields in extra
        foreach ($data as $key => $value) {
            if (!isset($handledKeys[$key])) {
                $this->extra[$key] = self::autoCast($value);
            }
        }
    }

    /**
     * Casts a property value using cached metadata.
     *
     * @param mixed $value
     * @param array<string, mixed> $meta
     */
    private static function castValueWithMeta(mixed $value, array $meta): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($meta['arrayOfTarget'] !== null && is_array($value)) {
            return self::castArrayOf($value, $meta['arrayOfTarget']);
        }

        if ($meta['targetEnum'] !== null) {
            $enumClass = $meta['targetEnum'];
            $enumVal = $enumClass::tryFrom($value);
            if ($enumVal !== null) {
                return $enumVal;
            }
        }

        if ($meta['targetClass'] !== null) {
            $targetClass = $meta['targetClass'];
            if (is_array($value)) {
                return self::factory($targetClass, $value);
            }
            if (is_string($value) && ($targetClass === RichText::class || is_subclass_of($targetClass, RichText::class))) {
                return new RichText(['text' => $value]);
            }
        }

        if ($meta['isBuiltinArray'] && is_array($value)) {
            return $value;
        }

        return self::autoCast($value);
    }

    /**
     * Recursively cast an array of objects.
     */
    public static function castArrayOf(array $items, string $targetClass): array
    {
        $result = [];
        foreach ($items as $k => $item) {
            if (is_array($item)) {
                // If it's a list of lists (e.g. InlineKeyboardMarkup keyboard buttons)
                if (array_is_list($item) && !empty($item) && is_array($item[0])) {
                    $result[$k] = self::castArrayOf($item, $targetClass);
                } else {
                    $result[$k] = self::factory($targetClass, $item);
                }
            } else {
                $result[$k] = $item;
            }
        }
        return $result;
    }

    /**
     * Auto-casts raw values to Type or array of Types if applicable.
     */
    public static function autoCast(mixed $value): mixed
    {
        if (!is_array($value)) {
            return $value;
        }

        // If it's an associative array, wrap in base Type
        if (!array_is_list($value)) {
            return new self($value);
        }

        // It's a list
        $list = [];
        foreach ($value as $item) {
            $list[] = is_array($item) && !array_is_list($item) ? new self($item) : self::autoCast($item);
        }
        return $list;
    }

    /**
     * Creates a Type instance from array with polymorphic resolution.
     */
    public static function factory(string $className, array $data): Type
    {
        // Polymorphic resolution for abstract / union types
        $resolvedClass = self::resolvePolymorphicClass($className, $data);

        if (!class_exists($resolvedClass)) {
            // Fallback to base Type
            return new self($data);
        }

        return new $resolvedClass($data);
    }

    /**
     * Resolves concrete class for union/polymorphic base types.
     */
    protected static function resolvePolymorphicClass(string $className, array $data): string
    {
        // Method can be overridden or mapped for polymorphic Telegram types:
        // E.g. ChatMember -> ChatMemberOwner, ChatMemberAdministrator, etc.
        // InlineQueryResult -> InlineQueryResultArticle, etc.
        // MaybeInaccessibleMessage -> Message or InaccessibleMessage
        // ReactionType -> ReactionTypeEmoji, ReactionTypeCustomEmoji, etc.
        // MessageOrigin -> MessageOriginUser, MessageOriginHiddenUser, etc.

        if (method_exists($className, 'resolveChildClass')) {
            return $className::resolveChildClass($data);
        }

        return $className;
    }

    /**
     * Dynamic property getter supporting camelCase, snake_case, and extra properties.
     */
    public function __get(string $name): mixed
    {
        $class = static::class;
        $properties = self::$classPropertiesCache[$class] ??= self::resolveClassProperties($class);

        // 1. Direct property check (camelCase)
        if (isset($properties[$name])) {
            /** @var ReflectionProperty $prop */
            $prop = $properties[$name]['prop'];
            if ($prop->isInitialized($this)) {
                return $prop->getValue($this);
            }
            return null;
        }

        if (property_exists($this, $name)) {
            try {
                return $this->$name;
            } catch (\Error) {
                return null;
            }
        }

        // 2. Convert to snake_case and check
        $snake = self::toSnakeCase($name);
        if (isset($this->extra[$snake])) {
            return $this->extra[$snake];
        }

        // 3. Convert to camelCase and check
        $camel = self::toCamelCase($name);
        if (isset($properties[$camel])) {
            /** @var ReflectionProperty $prop */
            $prop = $properties[$camel]['prop'];
            if ($prop->isInitialized($this)) {
                return $prop->getValue($this);
            }
            return null;
        }

        if (property_exists($this, $camel)) {
            try {
                return $this->$camel;
            } catch (\Error) {
                return null;
            }
        }

        // 4. Raw check
        if (isset($this->raw[$snake])) {
            return self::autoCast($this->raw[$snake]);
        }
        if (isset($this->raw[$name])) {
            return self::autoCast($this->raw[$name]);
        }

        return null;
    }

    /**
     * Dynamic property setter.
     */
    public function __set(string $name, mixed $value): void
    {
        $camel = self::toCamelCase($name);
        if (property_exists($this, $camel)) {
            try {
                $this->$camel = $value;
                return;
            } catch (\Error) {
                // Asymmetric visibility restriction or uninitialized, write to extra
            }
        }

        $snake = self::toSnakeCase($name);
        $this->extra[$snake] = $value;
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }

    public function __unset(string $name): void
    {
        $snake = self::toSnakeCase($name);
        unset($this->extra[$snake]);
        unset($this->raw[$snake]);
        unset($this->raw[$name]);
    }

    // ArrayAccess implementation (supports snake_case and camelCase indexing)
    public function offsetExists(mixed $offset): bool
    {
        return $this->__isset((string)$offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->__get((string)$offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->__set((string)$offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->__unset((string)$offset);
    }

    /**
     * Determines whether the response is successful.
     * Always returns true for valid Type objects, and false for Error objects.
     */
    #[\NoDiscard]
    public function ok(): bool
    {
        return true;
    }

    /**
     * Alias for ok().
     */
    #[\NoDiscard]
    public function isOk(): bool
    {
        return true;
    }

    // IteratorAggregate
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->toArray());
    }

    // JsonSerializable
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function __toString(): string
    {
        return (string)json_encode($this->toArray(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function toJson(int $flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT): string
    {
        return (string)json_encode($this->toArray(), $flags);
    }

    /**
     * Converts the type and all nested elements into a plain array.
     */
    public function toArray(): array
    {
        $result = [];
        $class = static::class;
        $properties = self::$classPropertiesCache[$class] ??= self::resolveClassProperties($class);

        foreach ($properties as $propName => $meta) {
            /** @var ReflectionProperty $prop */
            $prop = $meta['prop'];
            if ($prop->isInitialized($this)) {
                $val = $prop->getValue($this);
                if ($val !== null) {
                    $result[$meta['fieldName']] = self::valueToArray($val);
                }
            }
        }

        // Merge extra dynamic fields
        foreach ($this->extra as $k => $v) {
            $result[$k] = self::valueToArray($v);
        }

        return $result;
    }

    /**
     * Converts a nested value into plain array.
     */
    protected static function valueToArray(mixed $value): mixed
    {
        if ($value instanceof Type) {
            return $value->toArray();
        }
        if ($value instanceof \BackedEnum) {
            return $value->value;
        }
        if (is_array($value)) {
            $out = [];
            foreach ($value as $k => $v) {
                $out[$k] = self::valueToArray($v);
            }
            return $out;
        }
        return $value;
    }

    /**
     * Get raw data as originally received.
     */
    public function getRawData(): array
    {
        return $this->raw;
    }

    public static function toSnakeCase(string $input): string
    {
        return strtolower((string)preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    public static function toCamelCase(string $input): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $input))));
    }
}
