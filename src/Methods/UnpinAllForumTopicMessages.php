<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to clear the list of pinned messages in a forum topic in a forum supergroup chat or a private chat with a user. In the case of a supergroup chat the bot must be an administrator in the chat for this to work and must have the can_pin_messages administrator right in the supergroup. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#unpinallforumtopicmessages
 */
#[ApiMethod('unpinAllForumTopicMessages', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class UnpinAllForumTopicMessages extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * Unique identifier for the target message thread of the forum topic
     */
    #[Field('message_thread_id', required: true)]
    public ?int $messageThreadId = null;

    public function __construct(
        int|string|null $chatId = null,
        ?int $messageThreadId = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($messageThreadId !== null) $this->messageThreadId = $messageThreadId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
