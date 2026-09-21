<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * Describes data sent from a Web App to the bot.
 *
 * @link https://core.telegram.org/bots/api#webappdata
 */
class WebAppData extends Type
{
    /**
     * The data. Be aware that a bad client can send arbitrary data in this field.
     */
    #[Field('data', required: true)]
    public private(set) string $data;

    /**
     * Text of the web_app keyboard button from which the Web App was opened. Be aware that a bad client can send arbitrary data in this field.
     */
    #[Field('button_text', required: true)]
    public private(set) string $buttonText;

}
