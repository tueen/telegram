<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use JsonSerializable;
use ReflectionClass;
use ReflectionProperty;
use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\RequiresUpload;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Types\Custom\InputFile;
use Tueen\Telegram\Types\Type;

abstract class Method implements JsonSerializable
{
    /**
     * Custom parameters stored for this method call.
     */
    protected array $customParameters = [];

    /**
     * Files attached for multipart upload.
     * @var array<string, InputFile>
     */
    protected array $files = [];

    /**
     * Cache of reflection properties and attributes per concrete Method class.
     * @var array<class-string, array<string, array<string, mixed>>>
     */
    private static array $methodPropertiesCache = [];

    /**
     * Cache of endpoint metadata per concrete Method class.
     * @var array<class-string, array<string, mixed>>
     */
    private static array $methodMetaCache = [];

    public string $endpoint {
        get {
            $class = static::class;
            $meta = self::$methodMetaCache[$class] ??= self::resolveMethodMeta($class);
            return $meta['endpoint'];
        }
    }

    public string $httpMethod {
        get {
            $class = static::class;
            $meta = self::$methodMetaCache[$class] ??= self::resolveMethodMeta($class);
            return $meta['httpMethod'];
        }
    }

    public ?ReturnType $returnTypeInfo {
        get {
            $class = static::class;
            $meta = self::$methodMetaCache[$class] ??= self::resolveMethodMeta($class);
            return $meta['returnType'];
        }
    }

    /**
     * Returns expected TelegramErrorCode items declared via #[ApiErrors].
     *
     * @return list<TelegramErrorCode>
     */
    public array $expectedErrors {
        get {
            $class = static::class;
            $meta = self::$methodMetaCache[$class] ??= self::resolveMethodMeta($class);
            return $meta['apiErrors'] ?? [];
        }
    }

    public bool $requiresMultipart {
        get {
            $class = static::class;
            $meta = self::$methodMetaCache[$class] ??= self::resolveMethodMeta($class);
            if ($meta['requiresUpload']) {
                return true;
            }

            return !empty($this->multipartFiles);
        }
    }

    public array $parameters {
        get {
            [$params] = $this->buildRequestData();
            return $params;
        }
    }

    /**
     * @return array<string, InputFile>
     */
    public array $multipartFiles {
        get {
            [, $files] = $this->buildRequestData();
            return $files;
        }
    }

    private static function resolveMethodMeta(string $class): array
    {
        $reflection = new ReflectionClass($class);
        $apiMethodAttrs = $reflection->getAttributes(ApiMethod::class);
        $endpoint = !empty($apiMethodAttrs) ? $apiMethodAttrs[0]->newInstance()->name : lcfirst($reflection->getShortName());
        $httpMethod = !empty($apiMethodAttrs) ? $apiMethodAttrs[0]->newInstance()->httpMethod : 'POST';

        $returnTypeAttrs = $reflection->getAttributes(ReturnType::class);
        $returnType = !empty($returnTypeAttrs) ? $returnTypeAttrs[0]->newInstance() : null;

        $requiresUploadAttrs = $reflection->getAttributes(RequiresUpload::class);
        $requiresUpload = !empty($requiresUploadAttrs);

        $apiErrorsAttrs = $reflection->getAttributes(ApiErrors::class);
        $apiErrors = !empty($apiErrorsAttrs) ? $apiErrorsAttrs[0]->newInstance()->errors : [];

        return [
            'endpoint' => $endpoint,
            'httpMethod' => $httpMethod,
            'returnType' => $returnType,
            'requiresUpload' => $requiresUpload,
            'apiErrors' => $apiErrors,
        ];
    }

    private static function resolveMethodProperties(string $class): array
    {
        $reflection = new ReflectionClass($class);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

        $cached = [];
        foreach ($properties as $prop) {
            if ($prop->isVirtual()) {
                continue;
            }

            $name = $prop->getName();
            if ($name === 'parameters' || $name === 'files' || $name === 'customParameters') {
                continue;
            }

            $fieldName = Type::toSnakeCase($name);
            $attrs = $prop->getAttributes(Field::class);
            if (!empty($attrs)) {
                $fieldName = $attrs[0]->newInstance()->name;
            }

            $cached[$name] = [
                'prop' => $prop,
                'fieldName' => $fieldName,
            ];
        }

        return $cached;
    }

    /**
     * Extracts nested InputFile instances (e.g. from inside media arrays).
     *
     * @param array<string, InputFile> $files
     */
    public static function extractNestedFiles(mixed $data, array &$files, int &$attachCounter): mixed
    {
        if ($data instanceof InputFile) {
            $attachName = 'attach_file_' . ($attachCounter++);
            $files[$attachName] = $data;
            return "attach://{$attachName}";
        }

        if ($data instanceof Type) {
            $arr = $data->toArray();
            return self::extractNestedFiles($arr, $files, $attachCounter);
        }

        if (is_array($data)) {
            $out = [];
            foreach ($data as $k => $v) {
                $out[$k] = self::extractNestedFiles($v, $files, $attachCounter);
            }
            return $out;
        }

        return $data;
    }

    /**
     * Extracts all parameters and files from properties.
     */
    public function buildRequestData(): array
    {
        $params = [];
        $files = [];

        $class = static::class;
        $properties = self::$methodPropertiesCache[$class] ??= self::resolveMethodProperties($class);

        $attachCounter = 0;

        foreach ($properties as $name => $info) {
            /** @var ReflectionProperty $prop */
            $prop = $info['prop'];
            if (!$prop->isInitialized($this)) {
                continue;
            }

            $val = $prop->getValue($this);
            if ($val === null) {
                continue;
            }

            $fieldName = $info['fieldName'];

            if ($val instanceof InputFile) {
                $files[$fieldName] = $val;
            } elseif (is_array($val) || $val instanceof Type) {
                $processed = self::extractNestedFiles($val, $files, $attachCounter);
                $params[$fieldName] = self::formatParamValue($processed);
            } else {
                $params[$fieldName] = self::formatParamValue($val);
            }
        }

        // Merge manual parameters & files
        foreach ($this->customParameters as $k => $v) {
            if ($v instanceof InputFile) {
                $files[$k] = $v;
            } elseif ($v !== null) {
                if (is_array($v) || $v instanceof Type) {
                    $processed = self::extractNestedFiles($v, $files, $attachCounter);
                    $params[$k] = self::formatParamValue($processed);
                } else {
                    $params[$k] = self::formatParamValue($v);
                }
            }
        }

        foreach ($this->files as $k => $f) {
            $files[$k] = $f;
        }

        return [$params, $files];
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
        $this->customParameters[$name] = $value;
        return $this;
    }

    /**
     * Handles extra forward-compatible named parameters.
     */
    public function handleExtraParameters(array $extra): static
    {
        foreach ($extra as $key => $value) {
            if (is_int($key)) {
                throw new \InvalidArgumentException("Extra parameters must be named arguments, positional arguments are not supported.");
            }
            if ($value instanceof InputFile) {
                $this->attachFile(Type::toSnakeCase((string)$key), $value);
            } else {
                $this->setParameter(Type::toSnakeCase((string)$key), $value);
            }
        }
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
            return $val->toArray();
        }
        if ($val instanceof \BackedEnum) {
            return $val->value;
        }
        if (is_array($val)) {
            return self::normalizeArrayParam($val);
        }

        return $val;
    }

    private static function normalizeArrayParam(array $arr): array
    {
        $out = [];
        foreach ($arr as $k => $item) {
            if ($item instanceof Type) {
                $out[$k] = $item->toArray();
            } elseif ($item instanceof \BackedEnum) {
                $out[$k] = $item->value;
            } elseif (is_array($item)) {
                $out[$k] = self::normalizeArrayParam($item);
            } else {
                $out[$k] = $item;
            }
        }
        return $out;
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return $this->parameters;
    }
}
