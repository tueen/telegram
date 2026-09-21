<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\InputRichMessage;

/**
 * Represents the content of a rich message to be sent as the result of an inline query.
 *
 * @link https://core.telegram.org/bots/api#inputrichmessagecontent
 */
class InputRichMessageContent extends InputMessageContent
{
    /**
     * The message to be sent. Only previously uploaded files may be used in the message.
     */
    #[Field('rich_message', required: true)]
    public private(set) InputRichMessage $richMessage;

}
