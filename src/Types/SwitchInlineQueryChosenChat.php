<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object represents an inline button that switches the current user to inline mode in a chosen chat, with an optional default inline query.
 *
 * @link https://core.telegram.org/bots/api#switchinlinequerychosenchat
 */
class SwitchInlineQueryChosenChat extends Type
{
    /**
     * Optional. The default inline query to be inserted in the input field. If left empty, only the bot's username will be inserted.
     */
    #[Field('query', required: false)]
    public private(set) ?string $query = null;

    /**
     * Optional. True, if private chats with users can be chosen
     */
    #[Field('allow_user_chats', required: false)]
    public private(set) ?bool $allowUserChats = null;

    /**
     * Optional. True, if private chats with bots can be chosen
     */
    #[Field('allow_bot_chats', required: false)]
    public private(set) ?bool $allowBotChats = null;

    /**
     * Optional. True, if group and supergroup chats can be chosen
     */
    #[Field('allow_group_chats', required: false)]
    public private(set) ?bool $allowGroupChats = null;

    /**
     * Optional. True, if channel chats can be chosen
     */
    #[Field('allow_channel_chats', required: false)]
    public private(set) ?bool $allowChannelChats = null;

}
