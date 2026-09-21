<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\ChatAdministratorRights;

/**
 * This object defines the criteria used to request a suitable chat. Information about the selected chat will be shared with the bot when the corresponding button is pressed. The bot will be granted requested rights in the chat if appropriate. More about requesting chats: https://core.telegram.org/bots/features#chat-and-user-selection.
 *
 * @link https://core.telegram.org/bots/api#keyboardbuttonrequestchat
 */
class KeyboardButtonRequestChat extends Type
{
    /**
     * Signed 32-bit identifier of the request, which will be received back in the ChatShared object. Must be unique within the message.
     */
    #[Field('request_id', required: true)]
    public private(set) int $requestId;

    /**
     * Pass True to request a channel chat, pass False to request a group or a supergroup chat
     */
    #[Field('chat_is_channel', required: true)]
    public private(set) bool $chatIsChannel;

    /**
     * Optional. Pass True to request a forum supergroup, pass False to request a non-forum chat. If not specified, no additional restrictions are applied.
     */
    #[Field('chat_is_forum', required: false)]
    public private(set) ?bool $chatIsForum = null;

    /**
     * Optional. Pass True to request a supergroup or a channel with a username, pass False to request a chat without a username. If not specified, no additional restrictions are applied.
     */
    #[Field('chat_has_username', required: false)]
    public private(set) ?bool $chatHasUsername = null;

    /**
     * Optional. Pass True to request a chat owned by the user. Otherwise, no additional restrictions are applied.
     */
    #[Field('chat_is_created', required: false)]
    public private(set) ?bool $chatIsCreated = null;

    /**
     * Optional. A JSON-serialized object listing the required administrator rights of the user in the chat. The rights must be a superset of bot_administrator_rights. If not specified, no additional restrictions are applied.
     */
    #[Field('user_administrator_rights', required: false)]
    public private(set) ?ChatAdministratorRights $userAdministratorRights = null;

    /**
     * Optional. A JSON-serialized object listing the required administrator rights of the bot in the chat. The rights must be a subset of user_administrator_rights. If not specified, no additional restrictions are applied.
     */
    #[Field('bot_administrator_rights', required: false)]
    public private(set) ?ChatAdministratorRights $botAdministratorRights = null;

    /**
     * Optional. Pass True to request a chat with the bot as a member. Otherwise, no additional restrictions are applied.
     */
    #[Field('bot_is_member', required: false)]
    public private(set) ?bool $botIsMember = null;

    /**
     * Optional. Pass True to request the chat's title
     */
    #[Field('request_title', required: false)]
    public private(set) ?bool $requestTitle = null;

    /**
     * Optional. Pass True to request the chat's username
     */
    #[Field('request_username', required: false)]
    public private(set) ?bool $requestUsername = null;

    /**
     * Optional. Pass True to request the chat's photo
     */
    #[Field('request_photo', required: false)]
    public private(set) ?bool $requestPhoto = null;

}
