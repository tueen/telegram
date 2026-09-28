<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * Describes a service message about a change in the price of direct messages sent to a channel chat.
 *
 * @link https://core.telegram.org/bots/api#directmessagepricechanged
 */
class DirectMessagePriceChanged extends Type
{
    /**
     * True, if direct messages are enabled for the channel chat; False otherwise
     */
    #[Field('are_direct_messages_enabled', required: true)]
    private(set) ?bool $areDirectMessagesEnabled = null;

    /**
     * Optional. The new number of Telegram Stars that must be paid by users for each direct message sent to the channel. Does not apply to users who have been exempted by administrators. Defaults to 0.
     */
    #[Field('direct_message_star_count', required: false)]
    private(set) ?int $directMessageStarCount = null;

}
