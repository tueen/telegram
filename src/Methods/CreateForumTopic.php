<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\ForumIconColor;
use Tueen\Telegram\Types\ForumTopic;

/**
 * Use this method to create a topic in a forum supergroup chat or a private chat with a user. In the case of a supergroup chat the bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator right. Returns information about the created topic as a ForumTopic object.
 *
 * @link https://core.telegram.org/bots/api#createforumtopic
 */
#[ApiMethod('createForumTopic', 'POST')]
#[ReturnType(ForumTopic::class, isArray: false)]
class CreateForumTopic extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * Topic name, 1-128 characters
     */
    #[Field('name', required: true)]
    public string $name;

    /**
     * Color of the topic icon in RGB format. Currently, must be one of 7322096 (0x6FB9F0), 16766590 (0xFFD67E), 13338331 (0xCB86DB), 9367192 (0x8EEE98), 16749490 (0xFF93B2), or 16478047 (0xFB6F5F).
     */
    #[Field('icon_color', required: false)]
    public ForumIconColor|int|null $iconColor = null;

    /**
     * Unique identifier of the custom emoji shown as the topic icon. Use getForumTopicIconStickers to get all allowed custom emoji identifiers.
     */
    #[Field('icon_custom_emoji_id', required: false)]
    public ?string $iconCustomEmojiId = null;

    public function __construct(
        int|string $chatId,
        string $name,
        ForumIconColor|int|null $iconColor = null,
        ?string $iconCustomEmojiId = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($name !== null) $this->name = $name;
        if ($iconColor !== null) $this->iconColor = $iconColor;
        if ($iconCustomEmojiId !== null) $this->iconCustomEmojiId = $iconCustomEmojiId;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
