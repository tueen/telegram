<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;

/**
 * Use this method to hide the 'General' topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights. The topic will be automatically closed if it was open. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#hidegeneralforumtopic
 */
#[ApiMethod('hideGeneralForumTopic', 'POST')]
#[ReturnType(Type::class, isArray: false)]
class HideGeneralForumTopic extends Method
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
