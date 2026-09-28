<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to decline a suggested post in a direct messages chat. The bot must have the 'can_manage_direct_messages' administrator right in the corresponding channel chat. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#declinesuggestedpost
 */
#[ApiMethod('declineSuggestedPost', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class DeclineSuggestedPost extends Method
{
    /**
     * Unique identifier for the target direct messages chat
     */
    #[Field('chat_id', required: true)]
    public int $chatId;

    /**
     * Identifier of a suggested post message to decline
     */
    #[Field('message_id', required: true)]
    public int $messageId;

    /**
     * Comment for the creator of the suggested post; 0-128 characters
     */
    #[Field('comment', required: false)]
    public ?string $comment = null;

    public function __construct(
        int $chatId,
        int $messageId,
        ?string $comment = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($messageId !== null) $this->messageId = $messageId;
        if ($comment !== null) $this->comment = $comment;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
