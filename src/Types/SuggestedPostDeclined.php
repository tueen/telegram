<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\Message;

/**
 * Describes a service message about the rejection of a suggested post.
 *
 * @link https://core.telegram.org/bots/api#suggestedpostdeclined
 */
class SuggestedPostDeclined extends Type
{
    /**
     * Optional. Message containing the suggested post. Note that the Message object in this field will not contain the reply_to_message field even if it itself is a reply.
     */
    #[Field('suggested_post_message', required: false)]
    public private(set) ?Message $suggestedPostMessage = null;

    /**
     * Optional. Comment with which the post was declined
     */
    #[Field('comment', required: false)]
    public private(set) ?string $comment = null;

}
