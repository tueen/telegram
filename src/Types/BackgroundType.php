<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * This object describes the type of a background. Currently, it can be one of
 * - BackgroundTypeFill
 * - BackgroundTypeWallpaper
 * - BackgroundTypePattern
 * - BackgroundTypeChatTheme
 *
 * @link https://core.telegram.org/bots/api#backgroundtype
 */
class BackgroundType extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {
        if (($data['type'] ?? '') === 'fill') return BackgroundTypeFill::class;
        if (($data['type'] ?? '') === 'wallpaper') return BackgroundTypeWallpaper::class;
        if (($data['type'] ?? '') === 'pattern') return BackgroundTypePattern::class;
        if (($data['type'] ?? '') === 'chat_theme') return BackgroundTypeChatTheme::class;
        return static::class;
    }
}
