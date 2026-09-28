<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\SuggestedPostRefundedReason;

/**
 * Describes a service message about a payment refund for a suggested post.
 *
 * @link https://core.telegram.org/bots/api#suggestedpostrefunded
 */
class SuggestedPostRefunded extends Type
{
    /**
     * Optional. Message containing the suggested post. Note that the Message object in this field will not contain the reply_to_message field even if it itself is a reply.
     */
    #[Field('suggested_post_message', required: false)]
    private(set) ?Message $suggestedPostMessage = null;

    /**
     * Reason for the refund. Currently, one of "post_deleted" if the post was deleted within 24 hours of being posted or removed from scheduled messages without being posted, or "payment_refunded" if the payer refunded their payment.
     */
    #[Field('reason', required: true)]
    private(set) SuggestedPostRefundedReason|string|null $reason = null;

}
