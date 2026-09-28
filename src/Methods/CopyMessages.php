<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\BotKickedException;
use Tueen\Telegram\Exceptions\ChatNotFoundException;
use Tueen\Telegram\Exceptions\NotEnoughRightsException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\MessageId;

/**
 * Use this method to copy messages of any kind. If some of the specified messages can't be found or copied, they are skipped. Service messages, paid media messages, giveaway messages, giveaway winners messages, and invoice messages can't be copied. A quiz poll can be copied only if the value of the field correct_option_ids is known to the bot. The method is analogous to the method forwardMessages, but the copied messages don't have a link to the original message. Album grouping is kept for copied messages. On success, an Array of MessageId of the sent messages is returned.
 *
 * @link https://core.telegram.org/bots/api#copymessages
 *
 * @throws ChatNotFoundException
 * @throws BotKickedException
 * @throws NotEnoughRightsException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('copyMessages', 'POST')]
#[ReturnType(MessageId::class, isArray: true)]
#[ApiErrors([TelegramErrorCode::ChatNotFound, TelegramErrorCode::BotKicked, TelegramErrorCode::NotEnoughRights, TelegramErrorCode::FloodWait])]
class CopyMessages extends Method
{
    /**
     * Unique identifier for the target chat or username of the target bot, supergroup or channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * Unique identifier for the chat where the original messages were sent (or username of the target bot, supergroup or channel in the format @username)
     */
    #[Field('from_chat_id', required: true)]
    public int|string|null $fromChatId = null;

    /**
     * A JSON-serialized list of 1-100 identifiers of messages in the chat from_chat_id to copy. The identifiers must be specified in a strictly increasing order.
     */
    #[Field('message_ids', required: true)]
    public ?array $messageIds = null;

    /**
     * Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
     */
    #[Field('message_thread_id', required: false)]
    public ?int $messageThreadId = null;

    /**
     * Identifier of the direct messages topic to which the messages will be sent; required if the messages are sent to a direct messages chat
     */
    #[Field('direct_messages_topic_id', required: false)]
    public ?int $directMessagesTopicId = null;

    /**
     * Sends the messages silently. Users will receive a notification with no sound.
     */
    #[Field('disable_notification', required: false)]
    public ?bool $disableNotification = null;

    /**
     * Protects the contents of the sent messages from forwarding and saving
     */
    #[Field('protect_content', required: false)]
    public ?bool $protectContent = null;

    /**
     * Pass True to copy the messages without their captions
     */
    #[Field('remove_caption', required: false)]
    public ?bool $removeCaption = null;

    public function __construct(
        int|string|null $chatId = null,
        int|string|null $fromChatId = null,
        ?array $messageIds = null,
        ?int $messageThreadId = null,
        ?int $directMessagesTopicId = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?bool $removeCaption = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($fromChatId !== null) $this->fromChatId = $fromChatId;
        if ($messageIds !== null) $this->messageIds = $messageIds;
        if ($messageThreadId !== null) $this->messageThreadId = $messageThreadId;
        if ($directMessagesTopicId !== null) $this->directMessagesTopicId = $directMessagesTopicId;
        if ($disableNotification !== null) $this->disableNotification = $disableNotification;
        if ($protectContent !== null) $this->protectContent = $protectContent;
        if ($removeCaption !== null) $this->removeCaption = $removeCaption;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
