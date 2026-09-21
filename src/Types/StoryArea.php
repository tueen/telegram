<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\StoryAreaPosition;
use Tueen\Telegram\Types\StoryAreaType;

/**
 * Describes a clickable area on a story media.
 *
 * @link https://core.telegram.org/bots/api#storyarea
 */
class StoryArea extends Type
{
    /**
     * Position of the area
     */
    #[Field('position', required: true)]
    public private(set) StoryAreaPosition $position;

    /**
     * Type of the area
     */
    #[Field('type', required: true)]
    public private(set) StoryAreaType $type;

}
