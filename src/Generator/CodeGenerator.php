<?php

declare(strict_types=1);

namespace Tueen\Telegram\Generator;

use Tueen\Telegram\Types\Type;

class CodeGenerator
{
    private array $spec;
    private string $typesDir;
    private string $methodsDir;

    public function __construct(string $specPath, string $baseSrcDir)
    {
        $this->spec = json_decode(file_get_contents($specPath), true);
        $this->typesDir = $baseSrcDir . '/Types';
        $this->methodsDir = $baseSrcDir . '/Methods';

        if (!is_dir($this->typesDir)) {
            mkdir($this->typesDir, 0777, true);
        }
        if (!is_dir($this->methodsDir)) {
            mkdir($this->methodsDir, 0777, true);
        }
    }

    public function run(): void
    {
        echo "Generating Types...\n";
        $this->generateTypes();

        echo "Generating Methods...\n";
        $this->generateMethods();

        echo "Updating Telegram facade docblocks with all 185 methods...\n";
        $this->generateTelegramDocblock();

        echo "Done generating code.\n";
    }

    private function generateTypes(): void
    {
        $types = $this->spec['types'] ?? [];

        foreach ($types as $name => $typeDef) {
            $code = $this->compileTypeClass($name, $typeDef);
            $filePath = $this->typesDir . '/' . $name . '.php';
            file_put_contents($filePath, $code);
        }
        echo "Generated " . count($types) . " types.\n";
    }

    private function compileTypeClass(string $name, array $typeDef): string
    {
        $description = implode("\n * ", $typeDef['description'] ?? []);
        $link = $typeDef['href'] ?? '';
        $subtypes = $typeDef['subtypes'] ?? null;
        $subtypeOf = $typeDef['subtype_of'] ?? null;

        $parentClass = 'Type';
        if (!empty($subtypeOf)) {
            $parentClass = $subtypeOf[0];
        }

        $fields = $typeDef['fields'] ?? [];

        $propLines = [];
        $imports = [
            'Tueen\Telegram\Types\Type',
            'Tueen\Telegram\Attributes\Field',
            'Tueen\Telegram\Attributes\ArrayOf',
        ];

        // Polymorphic parent resolution logic
        $polymorphicMethod = '';
        if (!empty($subtypes)) {
            $polymorphicMethod = $this->buildPolymorphicMethod($name, $subtypes);
        }

        foreach ($fields as $field) {
            $fieldName = $field['name'];
            $camelName = Type::toCamelCase($fieldName);
            $fieldTypes = $field['types'] ?? [];
            $required = $field['required'] ?? false;
            $fieldDesc = $field['description'] ?? '';

            $phpType = $this->mapTypesToPhp($fieldTypes, $required, $imports);
            $isArray = $this->isArrayType($fieldTypes);
            $itemType = $this->extractArrayItemType($fieldTypes);

            $propDoc = "    /**\n     * {$fieldDesc}\n";
            if ($isArray && $itemType) {
                $propDoc .= "     * @var {$itemType}[]|null\n";
            }
            $propDoc .= "     */";

            $attributes = [];
            $attributes[] = "    #[Field('{$fieldName}', required: " . ($required ? 'true' : 'false') . ")]";
            if ($isArray && $itemType && class_exists("Tueen\\Telegram\\Types\\{$itemType}")) {
                $attributes[] = "    #[ArrayOf({$itemType}::class)]";
            }

            $attrStr = implode("\n", $attributes);
            $default = $required ? '' : ' = null';
            // Asymmetric visibility in PHP 8.4: public private(set)
            $propLine = "{$propDoc}\n{$attrStr}\n    public private(set) {$phpType} \${$camelName}{$default};";
            $propLines[] = $propLine;
        }

        $propsCode = implode("\n\n", $propLines);
        $importsCode = implode("\n", array_unique(array_map(fn($i) => "use {$i};", $imports)));

        return <<<PHP
<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

{$importsCode}

/**
 * {$description}
 *
 * @link {$link}
 */
class {$name} extends {$parentClass}
{
{$propsCode}
{$polymorphicMethod}
}

PHP;
    }

    private function buildPolymorphicMethod(string $parentName, array $subtypes): string
    {
        $cases = [];
        foreach ($subtypes as $sub) {
            $subDef = $this->spec['types'][$sub] ?? [];
            $fields = $subDef['fields'] ?? [];

            // Find constant/discriminator field (like 'type', 'status', 'source')
            foreach ($fields as $f) {
                if (in_array($f['name'], ['type', 'status', 'source'], true)) {
                    $desc = $f['description'] ?? '';
                    // Try to match "always “xyz”" or "must be xyz"
                    if (preg_match('/(?:always|must be)\s+[“"]([^”"]+)[”"]/i', $desc, $m)) {
                        $val = $m[1];
                        $cases[] = "        if ((\$data['{$f['name']}'] ?? '') === '{$val}') return {$sub}::class;";
                    }
                }
            }
        }

        $casesStr = implode("\n", $cases);

        return <<<PHP

    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array \$data): string
    {
{$casesStr}
        return static::class;
    }
PHP;
    }

    private function generateMethods(): void
    {
        $methods = $this->spec['methods'] ?? [];

        foreach ($methods as $rawName => $methodDef) {
            $className = ucfirst($rawName);
            $code = $this->compileMethodClass($className, $rawName, $methodDef);
            $filePath = $this->methodsDir . '/' . $className . '.php';
            file_put_contents($filePath, $code);
        }
        echo "Generated " . count($methods) . " methods.\n";
    }

    private function compileMethodClass(string $className, string $methodName, array $methodDef): string
    {
        $description = implode("\n * ", $methodDef['description'] ?? []);
        $link = $methodDef['href'] ?? '';
        $returns = $methodDef['returns'] ?? ['Boolean'];
        $fields = $methodDef['fields'] ?? [];

        // Sort fields so required parameters come first, avoiding PHP deprecation warnings
        usort($fields, function (array $a, array $b): int {
            $aReq = ($a['required'] ?? false) ? 1 : 0;
            $bReq = ($b['required'] ?? false) ? 1 : 0;
            return $bReq <=> $aReq;
        });

        $imports = [
            'Tueen\Telegram\Methods\Method',
            'Tueen\Telegram\Attributes\ApiMethod',
            'Tueen\Telegram\Attributes\ReturnType',
            'Tueen\Telegram\Attributes\Field',
        ];

        [$returnClass, $returnIsArray] = $this->mapReturnType($returns, $imports);

        $params = [];
        $props = [];
        $constructBody = [];

        foreach ($fields as $field) {
            $fieldName = $field['name'];
            $camelName = Type::toCamelCase($fieldName);
            $fieldTypes = $field['types'] ?? [];
            $required = $field['required'] ?? false;
            $fieldDesc = $field['description'] ?? '';

            // Check if parameter can be a file
            $isFile = in_array('InputFile', $fieldTypes, true);
            if ($isFile) {
                $imports[] = 'Tueen\Telegram\Types\Custom\InputFile';
                $imports[] = 'Tueen\Telegram\Attributes\RequiresUpload';
            }

            $phpType = $this->mapMethodParamType($fieldTypes, $required, $imports);

            $propDoc = "    /**\n     * {$fieldDesc}\n     */";
            $attrs = [];
            $attrs[] = "    #[Field('{$fieldName}', required: " . ($required ? 'true' : 'false') . ")]";
            if ($isFile) {
                $attrs[] = "    #[RequiresUpload]";
            }

            $attrStr = implode("\n", $attrs);
            $defaultVal = $required ? '' : ' = null';

            $props[] = "{$propDoc}\n{$attrStr}\n    public {$phpType} \${$camelName}{$defaultVal};";

            $paramDoc = "{$phpType} \${$camelName}{$defaultVal}";
            $params[] = $paramDoc;

            $constructBody[] = "        if (\${$camelName} !== null) \$this->{$camelName} = \${$camelName};";
        }

        $propsCode = implode("\n\n", $props);
        $paramsCode = implode(",\n        ", $params);
        if (!empty($paramsCode)) {
            $paramsCode = "\n        " . $paramsCode . "\n    ";
        }
        $constructCode = implode("\n", $constructBody);
        $importsCode = implode("\n", array_unique(array_map(fn($i) => "use {$i};", $imports)));

        $arrayFlag = $returnIsArray ? 'true' : 'false';

        return <<<PHP
<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

{$importsCode}

/**
 * {$description}
 *
 * @link {$link}
 */
#[ApiMethod('{$methodName}', 'POST')]
#[ReturnType({$returnClass}::class, isArray: {$arrayFlag})]
class {$className} extends Method
{
{$propsCode}

    public function __construct({$paramsCode})
    {
{$constructCode}
    }

    public static function make({$paramsCode}): static
    {
        return new static({$this->buildMakeArgs($fields)});
    }
}

PHP;
    }

    private function buildMakeArgs(array $fields): string
    {
        $args = [];
        foreach ($fields as $field) {
            $c = Type::toCamelCase($field['name']);
            $args[] = "\${$c}";
        }
        return implode(', ', $args);
    }

    private function mapReturnType(array $returns, array &$imports): array
    {
        $first = $returns[0] ?? 'Boolean';
        $isArray = false;

        if (str_starts_with($first, 'Array of ')) {
            $isArray = true;
            $first = substr($first, 9);
            if (str_starts_with($first, 'Array of ')) {
                $first = substr($first, 9);
            }
        }

        if ($first === 'Boolean') {
            $imports[] = 'Tueen\Telegram\Types\Custom\BooleanResult';
            return ['BooleanResult', $isArray];
        }

        if ($first === 'Integer') {
            $imports[] = 'Tueen\Telegram\Types\Custom\IntegerResult';
            return ['IntegerResult', $isArray];
        }

        if ($first === 'String') {
            $imports[] = 'Tueen\Telegram\Types\Custom\StringResult';
            return ['StringResult', $isArray];
        }

        // Check if class exists in Types
        if (isset($this->spec['types'][$first])) {
            $imports[] = "Tueen\\Telegram\\Types\\{$first}";
            return [$first, $isArray];
        }

        $imports[] = 'Tueen\Telegram\Types\Type';
        return ['Type', $isArray];
    }

    private function isArrayType(array $types): bool
    {
        foreach ($types as $t) {
            if (str_starts_with($t, 'Array of ')) {
                return true;
            }
        }
        return false;
    }

    private function extractArrayItemType(array $types): ?string
    {
        foreach ($types as $t) {
            if (str_starts_with($t, 'Array of ')) {
                $item = substr($t, 9);
                if (str_starts_with($item, 'Array of ')) {
                    $item = substr($item, 9);
                }
                return $item;
            }
        }
        return null;
    }

    private function mapTypesToPhp(array $types, bool $required, array &$imports): string
    {
        $phpTypes = [];
        $hasNull = !$required;

        foreach ($types as $t) {
            if ($t === 'Integer') {
                $phpTypes[] = 'int';
            } elseif ($t === 'String') {
                $phpTypes[] = 'string';
            } elseif ($t === 'Boolean') {
                $phpTypes[] = 'bool';
            } elseif ($t === 'Float') {
                $phpTypes[] = 'float';
            } elseif (str_starts_with($t, 'Array of ')) {
                $phpTypes[] = 'array';
                $item = $this->extractArrayItemType([$t]);
                if ($item && isset($this->spec['types'][$item])) {
                    $imports[] = "Tueen\\Telegram\\Types\\{$item}";
                }
            } elseif (isset($this->spec['types'][$t])) {
                $imports[] = "Tueen\\Telegram\\Types\\{$t}";
                $phpTypes[] = $t;
            } else {
                $phpTypes[] = 'mixed';
            }
        }

        $phpTypes = array_unique($phpTypes);
        if (in_array('mixed', $phpTypes, true)) {
            return 'mixed';
        }

        $typeStr = implode('|', $phpTypes);
        if ($hasNull) {
            if (count($phpTypes) === 1) {
                return "?{$typeStr}";
            }
            return "{$typeStr}|null";
        }

        return $typeStr ?: 'mixed';
    }

    private function mapMethodParamType(array $types, bool $required, array &$imports): string
    {
        $phpTypes = [];

        foreach ($types as $t) {
            if ($t === 'Integer') {
                $phpTypes[] = 'int';
            } elseif ($t === 'String') {
                $phpTypes[] = 'string';
            } elseif ($t === 'Boolean') {
                $phpTypes[] = 'bool';
            } elseif ($t === 'Float') {
                $phpTypes[] = 'float';
            } elseif ($t === 'InputFile') {
                $phpTypes[] = 'InputFile';
            } elseif (str_starts_with($t, 'Array of ')) {
                $phpTypes[] = 'array';
            } elseif (isset($this->spec['types'][$t])) {
                $imports[] = "Tueen\\Telegram\\Types\\{$t}";
                $phpTypes[] = $t;
            } else {
                $phpTypes[] = 'mixed';
            }
        }

        $phpTypes = array_unique($phpTypes);
        if (in_array('mixed', $phpTypes, true)) {
            return 'mixed';
        }

        $typeStr = implode('|', $phpTypes);
        if (!$required) {
            if (count($phpTypes) === 1) {
                return "?{$typeStr}";
            }
            return "{$typeStr}|null";
        }

        return $typeStr ?: 'mixed';
    }

    private function generateTelegramDocblock(): void
    {
        $methods = $this->spec['methods'] ?? [];
        $lines = [];
        $allImports = [];

        foreach ($methods as $rawName => $methodDef) {
            $returns = $methodDef['returns'] ?? ['Boolean'];
            $fields = $methodDef['fields'] ?? [];

            // Sort fields so required come first
            usort($fields, function (array $a, array $b): int {
                $aReq = ($a['required'] ?? false) ? 1 : 0;
                $bReq = ($b['required'] ?? false) ? 1 : 0;
                return $bReq <=> $aReq;
            });

            $docReturnTypes = [];
            foreach ($returns as $ret) {
                $isArray = false;
                $curr = $ret;
                if (str_starts_with($curr, 'Array of ')) {
                    $isArray = true;
                    $curr = substr($curr, 9);
                    if (str_starts_with($curr, 'Array of ')) {
                        $curr = substr($curr, 9);
                    }
                }

                if ($curr === 'Boolean') {
                    $allImports[] = 'Tueen\Telegram\Types\Custom\BooleanResult';
                    $docReturnTypes[] = 'BooleanResult';
                } elseif ($curr === 'Integer') {
                    $allImports[] = 'Tueen\Telegram\Types\Custom\IntegerResult';
                    $docReturnTypes[] = 'IntegerResult';
                } elseif ($curr === 'String') {
                    $allImports[] = 'Tueen\Telegram\Types\Custom\StringResult';
                    $docReturnTypes[] = 'StringResult';
                } elseif (isset($this->spec['types'][$curr])) {
                    $allImports[] = "Tueen\\Telegram\\Types\\{$curr}";
                    if ($isArray) {
                        $allImports[] = 'Tueen\Telegram\Types\Custom\ArrayResult';
                        $docReturnTypes[] = "ArrayResult<{$curr}>";
                    } else {
                        $docReturnTypes[] = $curr;
                    }
                } else {
                    if ($isArray) {
                        $allImports[] = 'Tueen\Telegram\Types\Custom\ArrayResult';
                        $docReturnTypes[] = 'ArrayResult';
                    } else {
                        $allImports[] = 'Tueen\Telegram\Types\Type';
                        $docReturnTypes[] = 'Type';
                    }
                }
            }

            $retType = implode('|', array_unique($docReturnTypes)) ?: 'Type';

            $paramList = [];
            foreach ($fields as $field) {
                $c = Type::toCamelCase($field['name']);
                $required = $field['required'] ?? false;
                $fTypes = $field['types'] ?? [];

                if (in_array('InputFile', $fTypes, true)) {
                    $allImports[] = 'Tueen\Telegram\Types\Custom\InputFile';
                }

                $phpType = $this->mapMethodParamType($fTypes, $required, $allImports);

                $def = $required ? '' : ' = null';
                $paramList[] = "{$phpType} \${$c}{$def}";
            }

            $paramsStr = implode(', ', $paramList);
            $lines[] = " * @method {$retType} {$rawName}({$paramsStr})";
        }

        $allImports = array_unique($allImports);
        sort($allImports);
        $useStatements = implode("\n", array_map(fn($i) => "use {$i};", $allImports));

        $docblockContent = implode("\n", $lines);

        // 1. Write the full Mixin contract file in src/Contracts/TelegramMethods.php
        $contractsDir = dirname($this->typesDir) . '/Contracts';
        if (!is_dir($contractsDir)) {
            mkdir($contractsDir, 0777, true);
        }

        $mixinCode = <<<PHP
<?php

declare(strict_types=1);

namespace Tueen\Telegram\Contracts;

{$useStatements}

/**
 * Dynamic Telegram Bot API 10.3 Methods Mixin.
 *
 * This contract defines all 185 Telegram Bot API method signatures for IDE autocompletion,
 * parameter hints, and type safety, keeping the core Telegram client facade lightweight.
 *
{$docblockContent}
 */
abstract class TelegramMethods
{
}

PHP;

        $mixinPath = $contractsDir . '/TelegramMethods.php';
        file_put_contents($mixinPath, $mixinCode);
        echo "Updated Contracts/TelegramMethods.php mixin with " . count($methods) . " method signatures.\n";

        // 2. Ensure src/Telegram.php has a clean, concise docblock referencing the mixin
        $telegramFile = dirname($this->typesDir) . '/Telegram.php';
        $content = file_get_contents($telegramFile);

        $cleanDocblock = <<<PHP
/**
 * Tueen Telegram Client - The Royal Client for Telegram Bot API.
 *
 * @mixin \Tueen\Telegram\Contracts\TelegramMethods
 */
PHP;

        $pattern = '/\/\*\*\s*\n\s*\* Tueen Telegram Client - The Royal Client for Telegram Bot API\..*?\*\//s';
        $newContent = preg_replace($pattern, $cleanDocblock, $content);
        if ($newContent !== null) {
            file_put_contents($telegramFile, $newContent);
            echo "Updated Telegram.php with clean @mixin contract.\n";
        }
    }
}

