<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\ChatNotFoundException;
use Tueen\Telegram\Exceptions\MessageNotFoundException;
use Tueen\Telegram\Exceptions\NotEnoughRightsException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to decline a suggested post in a direct messages chat. The bot must have the 'can_manage_direct_messages' administrator right in the corresponding channel chat. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#declinesuggestedpost
 *
 * @throws ChatNotFoundException
 * @throws MessageNotFoundException
 * @throws NotEnoughRightsException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('declineSuggestedPost', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::ChatNotFound, TelegramErrorCode::MessageNotFound, TelegramErrorCode::NotEnoughRights, TelegramErrorCode::FloodWait])]
class DeclineSuggestedPost extends Method
{
    /**
     * Unique identifier for the target direct messages chat
     */
    #[Field('chat_id', required: true)]
    public ?int $chatId = null;

    /**
     * Identifier of a suggested post message to decline
     */
    #[Field('message_id', required: true)]
    public ?int $messageId = null;

    /**
     * Comment for the creator of the suggested post; 0-128 characters
     */
    #[Field('comment', required: false)]
    public ?string $comment = null;

    public function __construct(
        ?int $chatId = null,
        ?int $messageId = null,
        ?string $comment = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($messageId !== null) $this->messageId = $messageId;
        if ($comment !== null) $this->comment = $comment;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
