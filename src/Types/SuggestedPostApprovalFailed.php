<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Describes a service message about the failed approval of a suggested post. Currently, only caused by insufficient user funds at the time of approval.
 *
 * @link https://core.telegram.org/bots/api#suggestedpostapprovalfailed
 */
class SuggestedPostApprovalFailed extends Type
{
    /**
     * Optional. Message containing the suggested post whose approval has failed. Note that the Message object in this field will not contain the reply_to_message field even if it itself is a reply.
     */
    #[Field('suggested_post_message', required: false)]
    public private(set) ?Message $suggestedPostMessage = null;

    /**
     * Expected price of the post
     */
    #[Field('price', required: true)]
    public private(set) SuggestedPostPrice $price;

}
