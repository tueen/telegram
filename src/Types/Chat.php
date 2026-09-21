<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * This object represents a chat.
 *
 * @link https://core.telegram.org/bots/api#chat
 */
class Chat extends Type
{
    /**
     * Unique identifier for this chat. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this identifier.
     */
    #[Field('id', required: true)]
    public private(set) int $id;

    /**
     * Type of the chat, can be either "private", "group", "supergroup" or "channel"
     */
    #[Field('type', required: true)]
    public private(set) string $type;

    /**
     * Optional. Title, for supergroups, channels and group chats
     */
    #[Field('title', required: false)]
    public private(set) ?string $title = null;

    /**
     * Optional. Username, for private chats, supergroups and channels if available
     */
    #[Field('username', required: false)]
    public private(set) ?string $username = null;

    /**
     * Optional. First name of the other party in a private chat
     */
    #[Field('first_name', required: false)]
    public private(set) ?string $firstName = null;

    /**
     * Optional. Last name of the other party in a private chat
     */
    #[Field('last_name', required: false)]
    public private(set) ?string $lastName = null;

    /**
     * Optional. True, if the supergroup chat is a forum (has topics enabled)
     */
    #[Field('is_forum', required: false)]
    public private(set) ?bool $isForum = null;

    /**
     * Optional. True, if the chat is the direct messages chat of a channel
     */
    #[Field('is_direct_messages', required: false)]
    public private(set) ?bool $isDirectMessages = null;

}
