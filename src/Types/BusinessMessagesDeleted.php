<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object is received when messages are deleted from a connected business account.
 *
 * @link https://core.telegram.org/bots/api#businessmessagesdeleted
 */
class BusinessMessagesDeleted extends Type
{
    /**
     * Unique identifier of the business connection
     */
    #[Field('business_connection_id', required: true)]
    private(set) ?string $businessConnectionId = null;

    /**
     * Information about a chat in the business account. The bot may not have access to the chat or the corresponding user.
     */
    #[Field('chat', required: true)]
    private(set) ?Chat $chat = null;

    /**
     * The list of identifiers of deleted messages in the chat of the business account
     * @var Integer[]|null
     */
    #[Field('message_ids', required: true)]
    private(set) ?array $messageIds = null;

}
