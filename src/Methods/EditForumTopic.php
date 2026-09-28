<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\ChatNotFoundException;
use Tueen\Telegram\Exceptions\NotEnoughRightsException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to edit name and icon of a topic in a forum supergroup chat or a private chat with a user. In the case of a supergroup chat the bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights, unless it is the creator of the topic. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#editforumtopic
 *
 * @throws ChatNotFoundException
 * @throws NotEnoughRightsException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('editForumTopic', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::ChatNotFound, TelegramErrorCode::NotEnoughRights, TelegramErrorCode::FloodWait])]
class EditForumTopic extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * Unique identifier for the target message thread of the forum topic
     */
    #[Field('message_thread_id', required: true)]
    public ?int $messageThreadId = null;

    /**
     * New topic name, 0-128 characters. If not specified or empty, the current name of the topic will be kept.
     */
    #[Field('name', required: false)]
    public ?string $name = null;

    /**
     * New unique identifier of the custom emoji shown as the topic icon. Use getForumTopicIconStickers to get all allowed custom emoji identifiers. Pass an empty string to remove the icon. If not specified, the current icon will be kept.
     */
    #[Field('icon_custom_emoji_id', required: false)]
    public ?string $iconCustomEmojiId = null;

    public function __construct(
        int|string|null $chatId = null,
        ?int $messageThreadId = null,
        ?string $name = null,
        ?string $iconCustomEmojiId = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($messageThreadId !== null) $this->messageThreadId = $messageThreadId;
        if ($name !== null) $this->name = $name;
        if ($iconCustomEmojiId !== null) $this->iconCustomEmojiId = $iconCustomEmojiId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
