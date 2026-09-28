<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a service message about an edited forum topic.
 *
 * @link https://core.telegram.org/bots/api#forumtopicedited
 */
class ForumTopicEdited extends Type
{
    /**
     * Optional. New name of the topic, if it was edited
     */
    #[Field('name', required: false)]
    private(set) ?string $name = null;

    /**
     * Optional. New identifier of the custom emoji shown as the topic icon, if it was edited; an empty string if the icon was removed
     */
    #[Field('icon_custom_emoji_id', required: false)]
    private(set) ?string $iconCustomEmojiId = null;

}
