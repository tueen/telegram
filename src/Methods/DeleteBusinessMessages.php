<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Delete messages on behalf of a business account. Requires the can_delete_sent_messages business bot right to delete messages sent by the bot itself, or the can_delete_all_messages business bot right to delete any message. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#deletebusinessmessages
 */
#[ApiMethod('deleteBusinessMessages', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class DeleteBusinessMessages extends Method
{
    /**
     * Unique identifier of the business connection on behalf of which to delete the messages
     */
    #[Field('business_connection_id', required: true)]
    public string $businessConnectionId;

    /**
     * A JSON-serialized list of 1-100 identifiers of messages to delete. All messages must be from the same chat. See deleteMessage for limitations on which messages can be deleted.
     */
    #[Field('message_ids', required: true)]
    public array $messageIds;

    public function __construct(
        string $businessConnectionId,
        array $messageIds
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($messageIds !== null) $this->messageIds = $messageIds;
    }

    public static function make(
        string $businessConnectionId,
        array $messageIds
    ): static
    {
        return new static($businessConnectionId, $messageIds);
    }
}
