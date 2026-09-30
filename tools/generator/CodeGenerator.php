<?php

declare(strict_types=1);

namespace Tueen\Telegram\Generator;

use Tueen\Telegram\Types\Type;

class CodeGenerator
{
    public const array CONTEXTUAL_FIELDS = [
        'chat_id',
        'business_connection_id',
        'message_thread_id',
        'direct_messages_topic_id',
        'user_id',
        'message_id',
        'callback_query_id',
        'inline_query_id',
        'shipping_query_id',
        'pre_checkout_query_id',
        'guest_query_id',
        'inline_message_id',
    ];

    private array $spec;
    private array $errorsSpec = [];
    private string $typesDir;
    private string $methodsDir;

    public function __construct(string $specPath, string $baseSrcDir, ?string $errorsPath = null)
    {
        $this->spec = json_decode(file_get_contents($specPath), true);
        $errorsPath ??= dirname($specPath) . '/errors.json';
        if (file_exists($errorsPath)) {
            $this->errorsSpec = json_decode(file_get_contents($errorsPath), true) ?? [];
        }
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

        echo "Updating Telegram facade docblocks with all methods...\n";
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
        $imports = [];

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

            $phpType = $this->mapTypesToPhp($name, $fieldName, $fieldTypes, $required, $imports);
            $isArray = $this->isArrayType($fieldTypes);
            $itemType = $this->extractArrayItemType($fieldTypes);

            $propDoc = "    /**\n     * {$fieldDesc}\n";
            if ($isArray && $itemType) {
                $propDoc .= "     * @var {$itemType}[]|null\n";
            }
            $propDoc .= "     */";

            $attributes = [];
            $attributes[] = "    #[Field('{$fieldName}', required: " . ($required ? 'true' : 'false') . ")]";
            $imports[] = 'Tueen\Telegram\Attributes\Field';

            if ($isArray && $itemType && (isset($this->spec['types'][$itemType]) || class_exists("Tueen\\Telegram\\Types\\{$itemType}"))) {
                $attributes[] = "    #[ArrayOf({$itemType}::class)]";
                $imports[] = 'Tueen\Telegram\Attributes\ArrayOf';
            }

            $attrStr = implode("\n", $attributes);
            $default = ' = null';
            // Asymmetric visibility in PHP 8.4: read visibility defaults to public, so public is redundant
            $propLine = "{$propDoc}\n{$attrStr}\n    private(set) {$phpType} \${$camelName}{$default};";
            $propLines[] = $propLine;
        }

        $traitStatement = '';
        if ($name === 'Update') {
            $imports[] = 'Tueen\Telegram\Types\Concerns\HasUpdateHelpers';
            $traitStatement = "    use HasUpdateHelpers;\n\n";
        } elseif ($name === 'Message') {
            $imports[] = 'Tueen\Telegram\Types\Concerns\HasMessageHelpers';
            $traitStatement = "    use HasMessageHelpers;\n\n";
        } elseif ($name === 'Chat' || $name === 'ChatFullInfo') {
            $imports[] = 'Tueen\Telegram\Types\Concerns\HasChatHelpers';
            $traitStatement = "    use HasChatHelpers;\n\n";
        } elseif ($name === 'User') {
            $imports[] = 'Tueen\Telegram\Types\Concerns\HasUserHelpers';
            $traitStatement = "    use HasUserHelpers;\n\n";
        } elseif ($name === 'InlineKeyboardMarkup') {
            $imports[] = 'Tueen\Telegram\Types\Concerns\HasInlineKeyboardHelpers';
            $traitStatement = "    use HasInlineKeyboardHelpers;\n\n";
        } elseif ($name === 'ReplyKeyboardMarkup') {
            $imports[] = 'Tueen\Telegram\Types\Concerns\HasReplyKeyboardHelpers';
            $traitStatement = "    use HasReplyKeyboardHelpers;\n\n";
        }

        $propsCode = implode("\n\n", $propLines);

        $classBody = <<<PHP
/**
 * {$description}
 *
 * @link {$link}
 */
class {$name} extends {$parentClass}
{
{$traitStatement}{$propsCode}
{$polymorphicMethod}
}
PHP;

        // Filter out same-namespace imports (anything directly in Tueen\Telegram\Types\)
        $imports = array_filter($imports, function (string $i): bool {
            return !preg_match('/^Tueen\\\\Telegram\\\\Types\\\\[A-Za-z0-9_]+$/', $i);
        });

        // Filter out unused imports whose short class name does not appear in the class body / docblocks
        $imports = array_filter($imports, function (string $i) use ($classBody): bool {
            $shortName = substr(strrchr($i, '\\') ?: ('\\' . $i), 1);
            return (bool) preg_match('/\b' . preg_quote($shortName, '/') . '\b/', $classBody);
        });

        $allImports = array_unique($imports);
        sort($allImports);
        $useBlock = !empty($allImports) ? implode("\n", array_map(fn($i) => "use {$i};", $allImports)) . "\n\n" : '';

        return <<<PHP
<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

{$useBlock}{$classBody}

PHP;
    }

    private function buildPolymorphicMethod(string $parentName, array $subtypes): string
    {
        if ($parentName === 'MaybeInaccessibleMessage') {
            return <<<PHP

    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array \$data): string
    {
        if (isset(\$data['date']) && (int)\$data['date'] === 0) {
            return InaccessibleMessage::class;
        }

        return Message::class;
    }
PHP;
        }

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
            'Tueen\Telegram\Attributes\ApiMethod',
            'Tueen\Telegram\Attributes\ReturnType',
        ];

        [$returnClass, $returnIsArray] = $this->mapReturnType($returns, $imports);

        $params = [];
        $props = [];
        $constructBody = [];
        $hasSeenDefault = false;

        foreach ($fields as $field) {
            $fieldName = $field['name'];
            $camelName = Type::toCamelCase($fieldName);
            $fieldTypes = $field['types'] ?? [];
            $specRequired = $field['required'] ?? false;
            $isContextual = in_array($fieldName, self::CONTEXTUAL_FIELDS, true);
            $isOptional = !$specRequired || $isContextual || $hasSeenDefault;
            if ($isOptional) {
                $hasSeenDefault = true;
            }
            $fieldDesc = $field['description'] ?? '';

            // Check if parameter can be a file
            $isFile = in_array('InputFile', $fieldTypes, true);
            if ($isFile) {
                $imports[] = 'Tueen\Telegram\Types\Custom\InputFile';
                $imports[] = 'Tueen\Telegram\Attributes\RequiresUpload';
            }

            $phpType = $this->mapMethodParamType($methodName, $fieldName, $fieldTypes, !$isOptional, $imports);

            $propDoc = "    /**\n     * {$fieldDesc}\n     */";
            $attrs = [];
            $attrs[] = "    #[Field('{$fieldName}', required: " . ($specRequired ? 'true' : 'false') . ")]";
            $imports[] = 'Tueen\Telegram\Attributes\Field';

            if ($isFile) {
                $attrs[] = "    #[RequiresUpload]";
            }

            $attrStr = implode("\n", $attrs);
            $defaultVal = $isOptional ? ' = null' : '';

            $props[] = "{$propDoc}\n{$attrStr}\n    public {$phpType} \${$camelName}{$defaultVal};";

            $paramDoc = "{$phpType} \${$camelName}{$defaultVal}";
            $params[] = $paramDoc;

            if (!$isOptional) {
                $constructBody[] = "        \$this->{$camelName} = \${$camelName};";
            } else {
                $constructBody[] = "        if (\${$camelName} !== null) \$this->{$camelName} = \${$camelName};";
            }
        }

        $params[] = 'mixed ...$extra';
        $constructBody[] = "        if (\$extra) \$this->handleExtraParameters(\$extra);";

        $propsCode = implode("\n\n", $props);
        $paramsCode = implode(",\n        ", $params);
        if (!empty($paramsCode)) {
            $paramsCode = "\n        " . $paramsCode . "\n    ";
        }
        $constructCode = implode("\n", $constructBody);

        $arrayFlag = $returnIsArray ? 'true' : 'false';

        $methodErrors = $this->errorsSpec['methods'][$methodName] ?? [];
        $apiErrorAttributes = '';
        $throwsDoc = '';
        if (!empty($methodErrors)) {
            $imports[] = 'Tueen\Telegram\Attributes\ApiErrors';
            $imports[] = 'Tueen\Telegram\Enums\TelegramErrorCode';
            $errorCases = [];
            $throwsClasses = [];
            foreach ($methodErrors as $errId) {
                if (isset($this->errorsSpec['errors'][$errId])) {
                    $errInfo = $this->errorsSpec['errors'][$errId];
                    $errorCases[] = "TelegramErrorCode::{$errInfo['enum']}";
                    $throwsClasses[] = $errInfo['exception'];
                    $imports[] = "Tueen\\Telegram\\Exceptions\\{$errInfo['exception']}";
                }
            }
            if (!empty($errorCases)) {
                $casesStr = implode(', ', $errorCases);
                $apiErrorAttributes = "\n#[ApiErrors([{$casesStr}])]";
            }
            if (!empty($throwsClasses)) {
                $uniqueThrows = array_unique($throwsClasses);
                $uniqueThrows[] = 'ApiException';
                $imports[] = 'Tueen\Telegram\Exceptions\ApiException';
                $throwsLines = array_map(fn($c) => " * @throws {$c}", $uniqueThrows);
                $throwsDoc = "\n *\n" . implode("\n", $throwsLines);
            }
        }

        $methodBody = <<<PHP
/**
 * {$description}
 *
 * @link {$link}{$throwsDoc}
 */
#[ApiMethod('{$methodName}', 'POST')]
#[ReturnType({$returnClass}::class, isArray: {$arrayFlag})]{$apiErrorAttributes}
class {$className} extends Method
{
{$propsCode}

    public function __construct({$paramsCode})
    {
{$constructCode}
    }
}
PHP;

        $imports = array_filter($imports, fn($i) => !str_starts_with($i, 'Tueen\\Telegram\\Methods\\') && $i !== 'Method');
        $imports = array_filter($imports, function (string $i) use ($methodBody): bool {
            $shortName = substr(strrchr($i, '\\') ?: ('\\' . $i), 1);
            return (bool) preg_match('/\b' . preg_quote($shortName, '/') . '\b/', $methodBody);
        });

        $allImports = array_unique($imports);
        sort($allImports);
        $useBlock = !empty($allImports) ? implode("\n", array_map(fn($i) => "use {$i};", $allImports)) . "\n\n" : '';

        return <<<PHP
<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

{$useBlock}{$methodBody}

PHP;
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

    private function resolveEnumForMethodParam(string $methodName, string $fieldName): ?string
    {
        if (in_array($fieldName, ['parse_mode', 'question_parse_mode', 'explanation_parse_mode', 'description_parse_mode', 'text_parse_mode'], true)) {
            return 'ParseMode';
        }
        if ($fieldName === 'currency') {
            return 'Currency';
        }
        if ($fieldName === 'icon_color') {
            return 'ForumIconColor';
        }
        if ($fieldName === 'active_period') {
            return 'StoryActivePeriod';
        }

        return match ("{$methodName}.{$fieldName}") {
            'sendChatAction.action' => 'ChatAction',
            'sendDice.emoji' => 'DiceEmoji',
            'sendPoll.type' => 'PollType',
            'uploadStickerFile.sticker_format',
            'setStickerSetThumbnail.format' => 'StickerFormat',
            'createNewStickerSet.sticker_type' => 'StickerType',
            'createForumTopic.icon_color' => 'ForumIconColor',
            'postStory.active_period',
            'repostStory.active_period' => 'StoryActivePeriod',
            'answerChatJoinRequestQuery.result' => 'ChatJoinRequestResult',
            default => null,
        };
    }

    private function resolveEnumForTypeProperty(string $typeName, string $fieldName): ?string
    {
        if (in_array($fieldName, ['parse_mode', 'question_parse_mode', 'explanation_parse_mode', 'description_parse_mode', 'text_parse_mode'], true)) {
            return 'ParseMode';
        }
        if ($fieldName === 'currency') {
            return 'Currency';
        }
        if ($fieldName === 'icon_color') {
            return 'ForumIconColor';
        }
        if ($fieldName === 'active_period') {
            return 'StoryActivePeriod';
        }

        if ($typeName === 'Chat' && $fieldName === 'type') return 'ChatType';
        if (str_starts_with($typeName, 'ChatMember') && $fieldName === 'status') return 'ChatMemberStatus';
        if ($typeName === 'MessageEntity' && $fieldName === 'type') return 'MessageEntityType';
        if ($typeName === 'Poll' && $fieldName === 'type') return 'PollType';
        if ($typeName === 'Sticker' && $fieldName === 'format') return 'StickerFormat';
        if ($typeName === 'Sticker' && $fieldName === 'type') return 'StickerType';
        if ($typeName === 'StickerSet' && $fieldName === 'sticker_type') return 'StickerType';
        if ($typeName === 'InputSticker' && $fieldName === 'format') return 'StickerFormat';
        if ($typeName === 'Dice' && $fieldName === 'emoji') return 'DiceEmoji';
        if ($typeName === 'MaskPosition' && $fieldName === 'point') return 'MaskPositionPoint';
        if (str_starts_with($typeName, 'BotCommandScope') && $fieldName === 'type') return 'BotCommandScopeType';
        if (str_starts_with($typeName, 'MenuButton') && $fieldName === 'type') return 'MenuButtonType';
        if (str_starts_with($typeName, 'ReactionType') && $fieldName === 'type') return 'ReactionTypeType';
        if (str_starts_with($typeName, 'MessageOrigin') && $fieldName === 'type') return 'MessageOriginType';
        if (str_starts_with($typeName, 'InputMedia') && $fieldName === 'type') return 'InputMediaType';
        if (str_starts_with($typeName, 'PaidMedia') && $fieldName === 'type') return 'PaidMediaType';
        if (str_starts_with($typeName, 'InputPaidMedia') && $fieldName === 'type') return 'InputPaidMediaType';
        if (str_starts_with($typeName, 'BackgroundFill') && $fieldName === 'type') return 'BackgroundFillType';
        if (str_starts_with($typeName, 'BackgroundType') && $fieldName === 'type') return 'BackgroundTypeType';
        if (str_starts_with($typeName, 'ChatBoostSource') && $fieldName === 'source') return 'ChatBoostSourceSource';
        if (str_starts_with($typeName, 'StoryAreaType') && $fieldName === 'type') return 'StoryAreaTypeType';
        if (str_starts_with($typeName, 'TransactionPartner') && $fieldName === 'type') return 'TransactionPartnerType';
        if (str_starts_with($typeName, 'RevenueWithdrawalState') && $fieldName === 'type') return 'RevenueWithdrawalStateType';
        if (str_starts_with($typeName, 'OwnedGift') && $fieldName === 'type') return 'OwnedGiftType';
        if ($typeName === 'UniqueGiftInfo' && $fieldName === 'origin') return 'UniqueGiftInfoOrigin';
        if ($typeName === 'UniqueGiftModel' && $fieldName === 'rarity') return 'UniqueGiftModelRarity';
        if ($typeName === 'SuggestedPostInfo' && $fieldName === 'state') return 'SuggestedPostInfoState';
        if ($typeName === 'SuggestedPostRefunded' && $fieldName === 'reason') return 'SuggestedPostRefundedReason';
        if ($typeName === 'VideoQuality' && $fieldName === 'codec') return 'VideoQualityCodec';
        if (str_starts_with($typeName, 'InputProfilePhoto') && $fieldName === 'type') return 'InputProfilePhotoType';
        if (str_starts_with($typeName, 'InputStoryContent') && $fieldName === 'type') return 'InputStoryContentType';
        if (str_starts_with($typeName, 'PassportElementError') && $fieldName === 'type') return 'PassportType';
        if (str_starts_with($typeName, 'PassportElementError') && $fieldName === 'source') return 'PassportSource';
        if (str_starts_with($typeName, 'InlineQueryResult') && $fieldName === 'type') return 'InlineQueryResultType';
        if (($typeName === 'InlineKeyboardButton' || $typeName === 'KeyboardButton') && $fieldName === 'style') return 'ButtonStyle';
        if ($typeName === 'StarSubscription' && in_array($fieldName, ['status', 'state'], true)) return 'SubscriptionState';
        if (str_starts_with($typeName, 'InputRichBlock') && $fieldName === 'buttons_align') return 'InputRichBlockButtonsAlign';
        if (str_starts_with($typeName, 'InputRichBlock') && $fieldName === 'type') return 'InputRichBlockType';
        if (str_starts_with($typeName, 'RichBlock') && $fieldName === 'buttons_align') return 'RichBlockButtonsAlign';
        if (str_starts_with($typeName, 'RichBlock') && $fieldName === 'list_item_type') return 'RichBlockListItemType';
        if (str_starts_with($typeName, 'RichBlockTableCell') && $fieldName === 'align') return 'RichBlockTableCellAlign';
        if (str_starts_with($typeName, 'RichBlockTableCell') && $fieldName === 'valign') return 'RichBlockTableCellValign';
        if (str_starts_with($typeName, 'RichBlock') && $fieldName === 'type') return 'RichBlockType';
        if (str_starts_with($typeName, 'RichText') && $fieldName === 'type') return 'RichTextType';

        return null;
    }

    private function mapTypesToPhp(string $typeName, string $fieldName, array $types, bool $required, array &$imports): string
    {
        $phpTypes = [];
        $hasNull = !$required;

        $enum = $this->resolveEnumForTypeProperty($typeName, $fieldName);
        if ($enum !== null) {
            $imports[] = "Tueen\\Telegram\\Enums\\{$enum}";
            $phpTypes[] = $enum;
        }

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
        if (count($phpTypes) === 1) {
            return "?{$typeStr}";
        }
        return "{$typeStr}|null";
    }

    private function mapMethodParamType(string $methodName, string $fieldName, array $types, bool $required, array &$imports): string
    {
        $phpTypes = [];

        $enum = $this->resolveEnumForMethodParam($methodName, $fieldName);
        if ($enum !== null) {
            $imports[] = "Tueen\\Telegram\\Enums\\{$enum}";
            $phpTypes[] = $enum;
        }

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

            $docReturnTypes[] = 'Error';
            $allImports[] = 'Tueen\Telegram\Types\Error';

            $retType = implode('|', array_unique($docReturnTypes)) ?: 'Type|Error';

            $paramList = [];
            $hasSeenDefault = false;
            foreach ($fields as $field) {
                $c = Type::toCamelCase($field['name']);
                $specRequired = $field['required'] ?? false;
                $isContextual = in_array($field['name'], self::CONTEXTUAL_FIELDS, true);
                $isOptional = !$specRequired || $isContextual || $hasSeenDefault;
                if ($isOptional) {
                    $hasSeenDefault = true;
                }
                $fTypes = $field['types'] ?? [];

                if (in_array('InputFile', $fTypes, true)) {
                    $allImports[] = 'Tueen\Telegram\Types\Custom\InputFile';
                }

                $phpType = $this->mapMethodParamType($rawName, $field['name'], $fTypes, !$isOptional, $allImports);

                $def = $isOptional ? ' = null' : '';
                $paramList[] = "{$phpType} \${$c}{$def}";
            }

            $allImports[] = 'Tueen\Telegram\Client\RequestOptions';
            $paramList[] = 'RequestOptions|array|null $_ = null';
            $paramList[] = 'mixed ...$extra';
            $paramsStr = implode(', ', $paramList);
            $lines[] = " * @method {$retType} {$rawName}({$paramsStr})";

            $methodErrors = $this->errorsSpec['methods'][$rawName] ?? [];
            if (!empty($methodErrors)) {
                $allImports[] = 'Tueen\Telegram\Exceptions\ApiException';
                foreach ($methodErrors as $errId) {
                    if (isset($this->errorsSpec['errors'][$errId])) {
                        $exClass = $this->errorsSpec['errors'][$errId]['exception'];
                        $allImports[] = "Tueen\\Telegram\\Exceptions\\{$exClass}";
                    }
                }
            }
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

        // Extract clean version string from schema (e.g. "Bot API 10.3" -> "10.3")
        $rawVersion = (string) ($this->spec['version'] ?? '10.3');
        if (preg_match('/(\d+(?:\.\d+)+)/', $rawVersion, $matches)) {
            $apiVersion = $matches[1];
        } else {
            $apiVersion = trim(str_ireplace('Bot API', '', $rawVersion)) ?: '10.3';
        }

        $mixinCode = <<<PHP
<?php

declare(strict_types=1);

namespace Tueen\Telegram\Contracts;

{$useStatements}

/**
 * Dynamic Telegram Bot API {$apiVersion} Methods Mixin.
 *
 * This contract defines all Telegram Bot API method signatures for IDE autocompletion,
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
        echo "Updated Contracts/TelegramMethods.php mixin with " . count($methods) . " method signatures (Bot API {$apiVersion}).\n";

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
            // 3. Ensure Telegram::BOT_API_VERSION is synchronized with schema version
            $versionPattern = '/(public const string BOT_API_VERSION\s*=\s*\')[^\']+(\';)/';
            if (preg_match($versionPattern, $newContent)) {
                $newContent = preg_replace($versionPattern, '${1}' . $apiVersion . '${2}', $newContent);
            }

            file_put_contents($telegramFile, $newContent);
            echo "Updated Telegram.php with clean @mixin contract and BOT_API_VERSION '{$apiVersion}'.\n";
        }
    }
}

