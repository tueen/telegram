<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\StoryAreaTypeType;

/**
 * Describes a story area pointing to an HTTP or tg:// link. Currently, a story can have up to 3 link areas.
 *
 * @link https://core.telegram.org/bots/api#storyareatypelink
 */
class StoryAreaTypeLink extends StoryAreaType
{
    /**
     * Type of the area, always "link"
     */
    #[Field('type', required: true)]
    public private(set) StoryAreaTypeType|string $type;

    /**
     * HTTP or tg:// URL to be opened when the area is clicked
     */
    #[Field('url', required: true)]
    public private(set) string $url;

}
