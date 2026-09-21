<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Describes a service message about the approval of a suggested post.
 *
 * @link https://core.telegram.org/bots/api#suggestedpostapproved
 */
class SuggestedPostApproved extends Type
{
    /**
     * Optional. Message containing the suggested post. Note that the Message object in this field will not contain the reply_to_message field even if it itself is a reply.
     */
    #[Field('suggested_post_message', required: false)]
    public private(set) ?Message $suggestedPostMessage = null;

    /**
     * Optional. Amount paid for the post
     */
    #[Field('price', required: false)]
    public private(set) ?SuggestedPostPrice $price = null;

    /**
     * Date when the post will be published
     */
    #[Field('send_date', required: true)]
    public private(set) int $sendDate;

}
