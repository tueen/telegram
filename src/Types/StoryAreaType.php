<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * Describes the type of a clickable area on a story. Currently, it can be one of
 * - StoryAreaTypeLocation
 * - StoryAreaTypeSuggestedReaction
 * - StoryAreaTypeLink
 * - StoryAreaTypeWeather
 * - StoryAreaTypeUniqueGift
 *
 * @link https://core.telegram.org/bots/api#storyareatype
 */
class StoryAreaType extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {
        if (($data['type'] ?? '') === 'location') return StoryAreaTypeLocation::class;
        if (($data['type'] ?? '') === 'suggested_reaction') return StoryAreaTypeSuggestedReaction::class;
        if (($data['type'] ?? '') === 'link') return StoryAreaTypeLink::class;
        if (($data['type'] ?? '') === 'weather') return StoryAreaTypeWeather::class;
        if (($data['type'] ?? '') === 'unique_gift') return StoryAreaTypeUniqueGift::class;
        return static::class;
    }
}
