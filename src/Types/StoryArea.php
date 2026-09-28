<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

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
    private(set) ?StoryAreaPosition $position = null;

    /**
     * Type of the area
     */
    #[Field('type', required: true)]
    private(set) ?StoryAreaType $type = null;

}
