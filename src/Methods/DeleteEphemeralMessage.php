<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to delete an ephemeral message. Note that it is not guaranteed that the user will receive the message deletion event, especially if they are offline. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#deleteephemeralmessage
 */
#[ApiMethod('deleteEphemeralMessage', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class DeleteEphemeralMessage extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * Identifier of the user who received the message
     */
    #[Field('receiver_user_id', required: true)]
    public ?int $receiverUserId = null;

    /**
     * Identifier of the ephemeral message to delete
     */
    #[Field('ephemeral_message_id', required: true)]
    public ?int $ephemeralMessageId = null;

    public function __construct(
        int|string|null $chatId = null,
        ?int $receiverUserId = null,
        ?int $ephemeralMessageId = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($receiverUserId !== null) $this->receiverUserId = $receiverUserId;
        if ($ephemeralMessageId !== null) $this->ephemeralMessageId = $ephemeralMessageId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
