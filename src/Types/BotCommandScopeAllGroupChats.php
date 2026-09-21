<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * Represents the scope of bot commands, covering all group and supergroup chats.
 *
 * @link https://core.telegram.org/bots/api#botcommandscopeallgroupchats
 */
class BotCommandScopeAllGroupChats extends BotCommandScope
{
    /**
     * Scope type, must be all_group_chats
     */
    #[Field('type', required: true)]
    public private(set) string $type;

}
