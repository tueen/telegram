<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use JsonSerializable;
use ReflectionClass;
use ReflectionProperty;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\RequiresUpload;
use Tueen\Telegram\Types\Custom\InputFile;
use Tueen\Telegram\Types\Type;

abstract class Method implements JsonSerializable
{
    /**
     * Parameters stored for this method call.
     */
    protected array $parameters = [];

    /**
     * Files attached for multipart upload.
     * @var array<string, InputFile>
     */
    protected array $files = [];

    public function getEndpoint(): string
    {
        $reflection = new ReflectionClass($this);
        $attrs = $reflection->getAttributes(ApiMethod::class);
        if (!empty($attrs)) {
            return $attrs[0]->newInstance()->name;
        }

        return lcfirst($reflection->getShortName());
    }

    public function getHttpMethod(): string
    {
        $reflection = new ReflectionClass($this);
        $attrs = $reflection->getAttributes(ApiMethod::class);
        if (!empty($attrs)) {
            return $attrs[0]->newInstance()->httpMethod;
        }

        return 'POST';
    }

    public function getReturnTypeInfo(): ?ReturnType
    {
        $reflection = new ReflectionClass($this);
        $attrs = $reflection->getAttributes(ReturnType::class);
        if (!empty($attrs)) {
            return $attrs[0]->newInstance();
        }

        return null;
    }

    public function requiresMultipart(): bool
    {
        $reflection = new ReflectionClass($this);
        $attrs = $reflection->getAttributes(RequiresUpload::class);
        if (!empty($attrs)) {
            return true;
        }

        return !empty($this->getMultipartFiles());
    }

    /**
     * Extracts all parameters and files from properties.
     */
    public function buildRequestData(): array
    {
        $params = [];
        $files = [];

        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

        foreach ($properties as $prop) {
            $name = $prop->getName();
            if ($name === 'parameters' || $name === 'files') {
                continue;
            }

            if (!$prop->isInitialized($this)) {
                continue;
            }

            $val = $prop->getValue($this);
            if ($val === null) {
                continue;
            }

            // Resolve field name
            $fieldName = $this->resolveFieldName($prop);

            if ($val instanceof InputFile) {
                $files[$fieldName] = $val;
            } else {
                $params[$fieldName] = self::formatParamValue($val);
            }
        }

        // Merge manual parameters & files
        foreach ($this->parameters as $k => $v) {
            if ($v instanceof InputFile) {
                $files[$k] = $v;
            } elseif ($v !== null) {
                $params[$k] = self::formatParamValue($v);
            }
        }

        foreach ($this->files as $k => $f) {
            $files[$k] = $f;
        }

        return [$params, $files];
    }

    public function getParameters(): array
    {
        [$params] = $this->buildRequestData();
        return $params;
    }

    /**
     * @return array<string, InputFile>
     */
    public function getMultipartFiles(): array
    {
        [, $files] = $this->buildRequestData();
        return $files;
    }

    /**
     * Helper to attach an InputFile.
     */
    public function attachFile(string $field, InputFile $file): static
    {
        $this->files[$field] = $file;
        return $this;
    }

    /**
     * Helper to set custom parameter.
     */
    public function setParameter(string $name, mixed $value): static
    {
        $this->parameters[$name] = $value;
        return $this;
    }

    private function resolveFieldName(ReflectionProperty $prop): string
    {
        $attrs = $prop->getAttributes(Field::class);
        if (!empty($attrs)) {
            return $attrs[0]->newInstance()->name;
        }

        return Type::toSnakeCase($prop->getName());
    }

    public static function formatParamValue(mixed $val): mixed
    {
        if ($val instanceof Type) {
            return json_encode($val->toArray(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        if ($val instanceof \BackedEnum) {
            return $val->value;
        }
        if (is_array($val)) {
            // Check if it contains complex types or objects
            $arrayVal = array_map(function ($item) {
                if ($item instanceof Type) {
                    return $item->toArray();
                }
                if ($item instanceof \BackedEnum) {
                    return $item->value;
                }
                return $item;
            }, $val);

            return json_encode($arrayVal, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        if (is_bool($val)) {
            return $val ? 'true' : 'false';
        }

        return $val;
    }

    public function jsonSerialize(): array
    {
        return $this->getParameters();
    }
}
