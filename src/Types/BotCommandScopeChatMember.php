<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\BotCommandScopeType;

/**
 * Represents the scope of bot commands, covering a specific member of a group or supergroup chat.
 *
 * @link https://core.telegram.org/bots/api#botcommandscopechatmember
 */
class BotCommandScopeChatMember extends BotCommandScope
{
    /**
     * Scope type, must be chat_member
     */
    #[Field('type', required: true)]
    public private(set) BotCommandScopeType|string $type;

    /**
     * Unique identifier for the target chat or username of the target supergroup in the format @username. Channel direct messages chats and channel chats aren't supported.
     */
    #[Field('chat_id', required: true)]
    public private(set) int|string $chatId;

    /**
     * Unique identifier of the target user
     */
    #[Field('user_id', required: true)]
    public private(set) int $userId;

}
