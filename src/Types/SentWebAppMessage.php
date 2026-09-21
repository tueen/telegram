<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * Describes an inline message sent by a Web App on behalf of a user.
 *
 * @link https://core.telegram.org/bots/api#sentwebappmessage
 */
class SentWebAppMessage extends Type
{
    /**
     * Optional. Identifier of the sent inline message. Available only if there is an inline keyboard attached to the message.
     */
    #[Field('inline_message_id', required: false)]
    public private(set) ?string $inlineMessageId = null;

}
