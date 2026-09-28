<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a custom keyboard with reply options (see Introduction to bots for details and examples). Not supported in channels and for messages sent on behalf of a business account.
 *
 * @link https://core.telegram.org/bots/api#replykeyboardmarkup
 */
class ReplyKeyboardMarkup extends Type
{
    /**
     * Array of button rows, each represented by an Array of KeyboardButton objects
     * @var KeyboardButton[]|null
     */
    #[Field('keyboard', required: true)]
    #[ArrayOf(KeyboardButton::class)]
    private(set) array $keyboard;

    /**
     * Optional. Requests clients to always show the keyboard when the regular keyboard is hidden. Defaults to False, in which case the custom keyboard can be hidden and opened with a keyboard icon.
     */
    #[Field('is_persistent', required: false)]
    private(set) ?bool $isPersistent = null;

    /**
     * Optional. Requests clients to resize the keyboard vertically for optimal fit (e.g., make the keyboard smaller if there are just two rows of buttons). Defaults to False, in which case the custom keyboard is always of the same height as the app's standard keyboard.
     */
    #[Field('resize_keyboard', required: false)]
    private(set) ?bool $resizeKeyboard = null;

    /**
     * Optional. Requests clients to hide the keyboard as soon as it's been used. The keyboard will still be available, but clients will automatically display the usual letter-keyboard in the chat - the user can press a special button in the input field to see the custom keyboard again. Defaults to False.
     */
    #[Field('one_time_keyboard', required: false)]
    private(set) ?bool $oneTimeKeyboard = null;

    /**
     * Optional. The placeholder to be shown in the input field when the keyboard is active; 1-64 characters
     */
    #[Field('input_field_placeholder', required: false)]
    private(set) ?string $inputFieldPlaceholder = null;

    /**
     * Optional. Use this parameter if you want to show the keyboard to specific users only. Targets: 1) users that are @mentioned in the text of the Message object; 2) if the bot's message is a reply to a message in the same chat and forum topic, sender of the original message. Example: A user requests to change the bot's language, bot replies to the request with a keyboard to select the new language. Other users in the group don't see the keyboard.
     */
    #[Field('selective', required: false)]
    private(set) ?bool $selective = null;

    /**
     * Optional. Pass True if the reply interface must be shown to the user, as if they had manually selected the bot's message and tapped 'Reply'
     */
    #[Field('force_reply', required: false)]
    private(set) ?bool $forceReply = null;

}
