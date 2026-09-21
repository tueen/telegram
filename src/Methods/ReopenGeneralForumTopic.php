<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to reopen a closed 'General' topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights. The topic will be automatically unhidden if it was hidden. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#reopengeneralforumtopic
 */
#[ApiMethod('reopenGeneralForumTopic', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class ReopenGeneralForumTopic extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    public function __construct(
        int|string $chatId,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
