<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * Describes a service message about a change in the price of paid messages within a chat.
 *
 * @link https://core.telegram.org/bots/api#paidmessagepricechanged
 */
class PaidMessagePriceChanged extends Type
{
    /**
     * The new number of Telegram Stars that must be paid by non-administrator users of the supergroup chat for each sent message
     */
    #[Field('paid_message_star_count', required: true)]
    private(set) ?int $paidMessageStarCount = null;

}
