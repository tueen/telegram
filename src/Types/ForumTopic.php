<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\ForumIconColor;

/**
 * This object represents a forum topic.
 *
 * @link https://core.telegram.org/bots/api#forumtopic
 */
class ForumTopic extends Type
{
    /**
     * Unique identifier of the forum topic
     */
    #[Field('message_thread_id', required: true)]
    private(set) ?int $messageThreadId = null;

    /**
     * Name of the topic
     */
    #[Field('name', required: true)]
    private(set) ?string $name = null;

    /**
     * Color of the topic icon in RGB format
     */
    #[Field('icon_color', required: true)]
    private(set) ForumIconColor|int|null $iconColor = null;

    /**
     * Optional. Unique identifier of the custom emoji shown as the topic icon
     */
    #[Field('icon_custom_emoji_id', required: false)]
    private(set) ?string $iconCustomEmojiId = null;

    /**
     * Optional. True, if the name of the topic wasn't specified explicitly by its creator and likely needs to be changed by the bot
     */
    #[Field('is_name_implicit', required: false)]
    private(set) ?bool $isNameImplicit = null;

}
