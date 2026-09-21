<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to delete multiple messages simultaneously. If some of the specified messages can't be found, they are skipped. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#deletemessages
 */
#[ApiMethod('deleteMessages', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class DeleteMessages extends Method
{
    /**
     * Unique identifier for the target chat or username of the target bot, supergroup or channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * A JSON-serialized list of 1-100 identifiers of messages to delete. See deleteMessage for limitations on which messages can be deleted.
     */
    #[Field('message_ids', required: true)]
    public array $messageIds;

    public function __construct(
        int|string $chatId,
        array $messageIds,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($messageIds !== null) $this->messageIds = $messageIds;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
