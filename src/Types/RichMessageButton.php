<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\RichText;
use Tueen\Telegram\Types\WebAppInfo;
use Tueen\Telegram\Types\LoginUrl;
use Tueen\Telegram\Types\SwitchInlineQueryChosenChat;
use Tueen\Telegram\Types\CopyTextButton;
use Tueen\Telegram\Types\DisabledButton;

/**
 * This object represents a button in a RichMessage. Exactly one of the fields other than text and style must be used to specify the type of the button.
 *
 * @link https://core.telegram.org/bots/api#richmessagebutton
 */
class RichMessageButton extends Type
{
    /**
     * Text of the button. May contain only plain text, RichTextCustomEmoji and RichTextDateTime entities.
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

    /**
     * Optional. Style of the button. Must be one of "danger", "success", "primary", or "link" (the button is shown as a regular link without borders). Apps may use theme-specific colors for the button background and text based on the style. The style "link" is allowed only for callback buttons.
     */
    #[Field('style', required: false)]
    public private(set) ?string $style = null;

    /**
     * Optional. HTTP or tg:// URL to be opened when the button is pressed. Links tg://user?id=<user_id> can be used to mention a user by their identifier without using a username, if this is allowed by their privacy settings.
     */
    #[Field('url', required: false)]
    public private(set) ?string $url = null;

    /**
     * Optional. Data to be sent in a callback query to the bot when the button is pressed, 1-64 bytes
     */
    #[Field('callback_data', required: false)]
    public private(set) ?string $callbackData = null;

    /**
     * Optional. Description of the Web App that will be launched when the user presses the button. The Web App will be able to send an arbitrary message on behalf of the user using the method answerWebAppQuery. Available only in private chats between a user and the bot. Not supported for messages sent on behalf of a business account.
     */
    #[Field('web_app', required: false)]
    public private(set) ?WebAppInfo $webApp = null;

    /**
     * Optional. An HTTPS URL used to automatically authorize the user. Can be used as a replacement for the Telegram Login Widget. Not supported for ephemeral messages.
     */
    #[Field('login_url', required: false)]
    public private(set) ?LoginUrl $loginUrl = null;

    /**
     * Optional. If set, pressing the button will prompt the user to select one of their chats, open that chat and insert the bot's username and the specified inline query in the input field. May be empty, in which case just the bot's username will be inserted. Not supported for messages sent in channel direct messages chats and on behalf of a business account.
     */
    #[Field('switch_inline_query', required: false)]
    public private(set) ?string $switchInlineQuery = null;

    /**
     * Optional. If set, pressing the button will insert the bot's username and the specified inline query in the current chat's input field. May be empty, in which case only the bot's username will be inserted. Not supported in channels and for messages sent in channel direct messages chats and on behalf of a business account.
     */
    #[Field('switch_inline_query_current_chat', required: false)]
    public private(set) ?string $switchInlineQueryCurrentChat = null;

    /**
     * Optional. If set, pressing the button will prompt the user to select one of their chats of the specified type, open that chat and insert the bot's username and the specified inline query in the input field. Not supported for messages sent in channel direct messages chats and on behalf of a business account.
     */
    #[Field('switch_inline_query_chosen_chat', required: false)]
    public private(set) ?SwitchInlineQueryChosenChat $switchInlineQueryChosenChat = null;

    /**
     * Optional. A button that copies the specified text to the clipboard
     */
    #[Field('copy_text', required: false)]
    public private(set) ?CopyTextButton $copyText = null;

    /**
     * Optional. If set, then the button is disabled and does nothing
     */
    #[Field('disabled', required: false)]
    public private(set) ?DisabledButton $disabled = null;

}
