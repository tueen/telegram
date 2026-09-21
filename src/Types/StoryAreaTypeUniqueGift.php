<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

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
    public private(set) string $type;

    /**
     * Unique name of the gift
     */
    #[Field('name', required: true)]
    public private(set) string $name;

}
