<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\StoryAreaTypeType;

/**
 * Describes a story area pointing to a unique gift. Currently, a story can have at most 1 unique gift area.
 *
 * @link https://core.telegram.org/bots/api#storyareatypeuniquegift
 */
class StoryAreaTypeUniqueGift extends StoryAreaType
{
    /**
     * Type of the area, always "unique_gift"
     */
    #[Field('type', required: true)]
    private(set) StoryAreaTypeType|string $type;

    /**
     * Unique name of the gift
     */
    #[Field('name', required: true)]
    private(set) string $name;

}
