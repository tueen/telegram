<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;

/**
 * Use this method to clear the list of pinned messages in a General forum topic. The bot must be an administrator in the chat for this to work and must have the can_pin_messages administrator right in the supergroup. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#unpinallgeneralforumtopicmessages
 */
#[ApiMethod('unpinAllGeneralForumTopicMessages', 'POST')]
#[ReturnType(Type::class, isArray: false)]
class UnpinAllGeneralForumTopicMessages extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    public function __construct(
        int|string $chatId
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
    }

    public static function make(
        int|string $chatId
    ): static
    {
        return new static($chatId);
    }
}
