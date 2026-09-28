<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to delete a message, including service messages, with the following limitations:
 * - A message can only be deleted if it was sent less than 48 hours ago.
 * - Service messages about a supergroup, channel, or forum topic creation can't be deleted.
 * - A dice message in a private chat can only be deleted if it was sent more than 24 hours ago.
 * - Bots can delete outgoing messages in private chats, groups, and supergroups.
 * - Bots can delete incoming messages in private chats.
 * - Bots granted can_post_messages permissions can delete outgoing messages in channels.
 * - If the bot is an administrator of a group, it can delete any message there.
 * - If the bot has can_delete_messages administrator right in a supergroup or a channel, it can delete any message there.
 * - If the bot has can_manage_direct_messages administrator right in a channel, it can delete any message in the corresponding direct messages chat.
 * Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#deletemessage
 */
#[ApiMethod('deleteMessage', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class DeleteMessage extends Method
{
    /**
     * Unique identifier for the target chat or username of the target bot, supergroup or channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * Identifier of the message to delete
     */
    #[Field('message_id', required: true)]
    public int $messageId;

    public function __construct(
        int|string $chatId,
        int $messageId,
        mixed ...$extra
    )
    {
        $this->chatId = $chatId;
        $this->messageId = $messageId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
