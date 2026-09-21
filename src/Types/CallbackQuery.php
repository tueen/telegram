<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object represents an incoming callback query from a callback button in an inline keyboard. If the button that originated the query was attached to a message sent by the bot, the field message will be present. If the button was attached to a message sent via the bot (in inline mode), the field inline_message_id will be present. Exactly one of the fields data or game_short_name will be present.
 *
 * @link https://core.telegram.org/bots/api#callbackquery
 */
class CallbackQuery extends Type
{
    /**
     * Unique identifier for this query
     */
    #[Field('id', required: true)]
    public private(set) string $id;

    /**
     * Sender
     */
    #[Field('from', required: true)]
    public private(set) User $from;

    /**
     * Optional. Message sent by the bot with the callback button that originated the query
     */
    #[Field('message', required: false)]
    public private(set) ?MaybeInaccessibleMessage $message = null;

    /**
     * Optional. Identifier of the message sent via the bot in inline mode, that originated the query
     */
    #[Field('inline_message_id', required: false)]
    public private(set) ?string $inlineMessageId = null;

    /**
     * Global identifier, uniquely corresponding to the chat to which the message with the callback button was sent. Useful for high scores in games.
     */
    #[Field('chat_instance', required: true)]
    public private(set) string $chatInstance;

    /**
     * Optional. Data associated with the callback button. Be aware that the message originated the query can contain no callback buttons with this data.
     */
    #[Field('data', required: false)]
    public private(set) ?string $data = null;

    /**
     * Optional. Short name of a Game to be returned, serves as the unique identifier for the game
     */
    #[Field('game_short_name', required: false)]
    public private(set) ?string $gameShortName = null;

}
