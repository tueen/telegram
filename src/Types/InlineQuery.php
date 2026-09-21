<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\User;
use Tueen\Telegram\Types\Location;

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
    public private(set) string $id;

    /**
     * Sender
     */
    #[Field('from', required: true)]
    public private(set) User $from;

    /**
     * Text of the query (up to 256 characters)
     */
    #[Field('query', required: true)]
    public private(set) string $query;

    /**
     * Offset of the results to be returned, can be controlled by the bot
     */
    #[Field('offset', required: true)]
    public private(set) string $offset;

    /**
     * Optional. Type of the chat from which the inline query was sent. Can be either "sender" for a private chat with the inline query sender, "private", "group", "supergroup", or "channel". The chat type should be always known for requests sent from official clients and most third-party clients, unless the request was sent from a secret chat.
     */
    #[Field('chat_type', required: false)]
    public private(set) ?string $chatType = null;

    /**
     * Optional. Sender location, only for bots that request user location
     */
    #[Field('location', required: false)]
    public private(set) ?Location $location = null;

}
