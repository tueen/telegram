<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\KeyboardButtonRequestUsers;
use Tueen\Telegram\Types\KeyboardButtonRequestChat;
use Tueen\Telegram\Types\KeyboardButtonRequestManagedBot;
use Tueen\Telegram\Types\KeyboardButtonPollType;
use Tueen\Telegram\Types\WebAppInfo;

/**
 * This object represents one button of the reply keyboard. At most one of the fields other than text, icon_custom_emoji_id, and style must be used to specify the type of the button. For simple text buttons, String can be used instead of this object to specify the button text.
 *
 * @link https://core.telegram.org/bots/api#keyboardbutton
 */
class KeyboardButton extends Type
{
    /**
     * Text of the button. If none of the fields other than text, icon_custom_emoji_id, and style are used, it will be sent as a message when the button is pressed.
     */
    #[Field('text', required: true)]
    public private(set) string $text;

    /**
     * Optional. Unique identifier of the custom emoji shown before the text of the button. Can only be used by bots that purchased additional usernames on Fragment or in the messages directly sent by the bot to private, group and supergroup chats if the owner of the bot has a Telegram Premium subscription.
     */
    #[Field('icon_custom_emoji_id', required: false)]
    public private(set) ?string $iconCustomEmojiId = null;

    /**
     * Optional. Style of the button. Must be one of "danger" (red), "success" (green) or "primary" (blue). If omitted, then an app-specific style is used.
     */
    #[Field('style', required: false)]
    public private(set) ?string $style = null;

    /**
     * Optional. If specified, pressing the button will open a list of suitable users. Identifiers of selected users will be sent to the bot in a "users_shared" service message. Available in private chats only.
     */
    #[Field('request_users', required: false)]
    public private(set) ?KeyboardButtonRequestUsers $requestUsers = null;

    /**
     * Optional. If specified, pressing the button will open a list of suitable chats. Tapping on a chat will send its identifier to the bot in a "chat_shared" service message. Available in private chats only.
     */
    #[Field('request_chat', required: false)]
    public private(set) ?KeyboardButtonRequestChat $requestChat = null;

    /**
     * Optional. If specified, pressing the button will ask the user to create and share a bot that will be managed by the current bot. Available for bots that enabled management of other bots in the @BotFather Mini App. Available in private chats only.
     */
    #[Field('request_managed_bot', required: false)]
    public private(set) ?KeyboardButtonRequestManagedBot $requestManagedBot = null;

    /**
     * Optional. If True, the user's phone number will be sent as a contact when the button is pressed. Available in private chats only.
     */
    #[Field('request_contact', required: false)]
    public private(set) ?bool $requestContact = null;

    /**
     * Optional. If True, the user's current location will be sent when the button is pressed. Available in private chats only.
     */
    #[Field('request_location', required: false)]
    public private(set) ?bool $requestLocation = null;

    /**
     * Optional. If specified, the user will be asked to create a poll and send it to the bot when the button is pressed. Available in private chats only.
     */
    #[Field('request_poll', required: false)]
    public private(set) ?KeyboardButtonPollType $requestPoll = null;

    /**
     * Optional. If specified, the described Web App will be launched when the button is pressed. The Web App will be able to send a "web_app_data" service message. Available in private chats only.
     */
    #[Field('web_app', required: false)]
    public private(set) ?WebAppInfo $webApp = null;

}
