<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\BotCommandScopeType;

/**
 * Represents the scope of bot commands, covering all private chats.
 *
 * @link https://core.telegram.org/bots/api#botcommandscopeallprivatechats
 */
class BotCommandScopeAllPrivateChats extends BotCommandScope
{
    /**
     * Scope type, must be all_private_chats
     */
    #[Field('type', required: true)]
    private(set) BotCommandScopeType|string|null $type = null;

}
