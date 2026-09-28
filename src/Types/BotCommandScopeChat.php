<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\BotCommandScopeType;

/**
 * Represents the scope of bot commands, covering a specific chat.
 *
 * @link https://core.telegram.org/bots/api#botcommandscopechat
 */
class BotCommandScopeChat extends BotCommandScope
{
    /**
     * Scope type, must be chat
     */
    #[Field('type', required: true)]
    private(set) BotCommandScopeType|string $type;

    /**
     * Unique identifier for the target chat or username of the target supergroup in the format @username. Channel direct messages chats and channel chats aren't supported.
     */
    #[Field('chat_id', required: true)]
    private(set) int|string $chatId;

}
