<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object contains information about a chat that was shared with the bot using a KeyboardButtonRequestChat button.
 *
 * @link https://core.telegram.org/bots/api#chatshared
 */
class ChatShared extends Type
{
    /**
     * Identifier of the request
     */
    #[Field('request_id', required: true)]
    public private(set) int $requestId;

    /**
     * Identifier of the shared chat. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier. The bot may not have access to the chat and could be unable to use this identifier, unless the chat is already known to the bot by some other means.
     */
    #[Field('chat_id', required: true)]
    public private(set) int $chatId;

    /**
     * Optional. Title of the chat, if the title was requested by the bot
     */
    #[Field('title', required: false)]
    public private(set) ?string $title = null;

    /**
     * Optional. Username of the chat, if the username was requested by the bot and available
     */
    #[Field('username', required: false)]
    public private(set) ?string $username = null;

    /**
     * Optional. Available sizes of the chat photo, if the photo was requested by the bot
     * @var PhotoSize[]|null
     */
    #[Field('photo', required: false)]
    #[ArrayOf(PhotoSize::class)]
    public private(set) ?array $photo = null;

}
