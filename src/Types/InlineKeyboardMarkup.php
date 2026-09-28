<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object represents an inline keyboard that appears right next to the message it belongs to.
 *
 * @link https://core.telegram.org/bots/api#inlinekeyboardmarkup
 */
class InlineKeyboardMarkup extends Type
{
    /**
     * Array of button rows, each represented by an Array of InlineKeyboardButton objects
     * @var InlineKeyboardButton[]|null
     */
    #[Field('inline_keyboard', required: true)]
    #[ArrayOf(InlineKeyboardButton::class)]
    private(set) array $inlineKeyboard;

    /**
     * Optional. Pass True if the reply interface must be shown to the user, as if they had manually selected the bot's message and tapped 'Reply'. The value of the field can't be changed when the inline keyboard is edited.
     */
    #[Field('force_reply', required: false)]
    private(set) ?bool $forceReply = null;

}
