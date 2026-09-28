<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\BotBlockedException;
use Tueen\Telegram\Exceptions\BotKickedException;
use Tueen\Telegram\Exceptions\ChatNotFoundException;
use Tueen\Telegram\Exceptions\MessageCantBeDeletedException;
use Tueen\Telegram\Exceptions\MessageNotFoundException;
use Tueen\Telegram\Exceptions\NotEnoughRightsException;
use Tueen\Telegram\Exceptions\RateLimitException;
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
 *
 * @throws ChatNotFoundException
 * @throws MessageNotFoundException
 * @throws MessageCantBeDeletedException
 * @throws BotBlockedException
 * @throws NotEnoughRightsException
 * @throws BotKickedException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('deleteMessage', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::ChatNotFound, TelegramErrorCode::MessageNotFound, TelegramErrorCode::MessageCantBeDeleted, TelegramErrorCode::BotBlocked, TelegramErrorCode::NotEnoughRights, TelegramErrorCode::BotKicked, TelegramErrorCode::FloodWait])]
class DeleteMessage extends Method
{
    /**
     * Unique identifier for the target chat or username of the target bot, supergroup or channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * Identifier of the message to delete
     */
    #[Field('message_id', required: true)]
    public ?int $messageId = null;

    public function __construct(
        int|string|null $chatId = null,
        ?int $messageId = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($messageId !== null) $this->messageId = $messageId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
