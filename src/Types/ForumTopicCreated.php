<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\ForumIconColor;

/**
 * This object represents a service message about a new forum topic created in the chat.
 *
 * @link https://core.telegram.org/bots/api#forumtopiccreated
 */
class ForumTopicCreated extends Type
{
    /**
     * Name of the topic
     */
    #[Field('name', required: true)]
    public private(set) string $name;

    /**
     * Color of the topic icon in RGB format
     */
    #[Field('icon_color', required: true)]
    public private(set) ForumIconColor|int $iconColor;

    /**
     * Optional. Unique identifier of the custom emoji shown as the topic icon
     */
    #[Field('icon_custom_emoji_id', required: false)]
    public private(set) ?string $iconCustomEmojiId = null;

    /**
     * Optional. True, if the name of the topic wasn't specified explicitly by its creator and likely needs to be changed by the bot
     */
    #[Field('is_name_implicit', required: false)]
    public private(set) ?bool $isNameImplicit = null;

}
