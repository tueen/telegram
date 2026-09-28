<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents an incoming inline query. When the user sends an empty query, your bot could return some default or trending results.
 *
 * @link https://core.telegram.org/bots/api#inlinequery
 */
class InlineQuery extends Type
{
    /**
     * Unique identifier for this query
     */
    #[Field('id', required: true)]
    private(set) ?string $id = null;

    /**
     * Sender
     */
    #[Field('from', required: true)]
    private(set) ?User $from = null;

    /**
     * Text of the query (up to 256 characters)
     */
    #[Field('query', required: true)]
    private(set) ?string $query = null;

    /**
     * Offset of the results to be returned, can be controlled by the bot
     */
    #[Field('offset', required: true)]
    private(set) ?string $offset = null;

    /**
     * Optional. Type of the chat from which the inline query was sent. Can be either "sender" for a private chat with the inline query sender, "private", "group", "supergroup", or "channel". The chat type should be always known for requests sent from official clients and most third-party clients, unless the request was sent from a secret chat.
     */
    #[Field('chat_type', required: false)]
    private(set) ?string $chatType = null;

    /**
     * Optional. Sender location, only for bots that request user location
     */
    #[Field('location', required: false)]
    private(set) ?Location $location = null;

}
