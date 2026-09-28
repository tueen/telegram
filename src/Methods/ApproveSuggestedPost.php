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
use Tueen\Telegram\Exceptions\DateInPastException;
use Tueen\Telegram\Exceptions\DateTooFarException;
use Tueen\Telegram\Exceptions\MessageNotFoundException;
use Tueen\Telegram\Exceptions\NotEnoughRightsException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to approve a suggested post in a direct messages chat. The bot must have the 'can_post_messages' administrator right in the corresponding channel chat. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#approvesuggestedpost
 *
 * @throws MessageNotFoundException
 * @throws DateTooFarException
 * @throws DateInPastException
 * @throws ChatNotFoundException
 * @throws NotEnoughRightsException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('approveSuggestedPost', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::MessageNotFound, TelegramErrorCode::DateTooFar, TelegramErrorCode::DateInPast, TelegramErrorCode::ChatNotFound, TelegramErrorCode::NotEnoughRights, TelegramErrorCode::FloodWait])]
class ApproveSuggestedPost extends Method
{
    /**
     * Unique identifier for the target direct messages chat
     */
    #[Field('chat_id', required: true)]
    public ?int $chatId = null;

    /**
     * Identifier of a suggested post message to approve
     */
    #[Field('message_id', required: true)]
    public ?int $messageId = null;

    /**
     * Point in time (Unix timestamp) when the post is expected to be published; omit if the date has already been specified when the suggested post was created. If specified, then the date must be not more than 2678400 seconds (30 days) in the future.
     */
    #[Field('send_date', required: false)]
    public ?int $sendDate = null;

    public function __construct(
        ?int $chatId = null,
        ?int $messageId = null,
        ?int $sendDate = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($messageId !== null) $this->messageId = $messageId;
        if ($sendDate !== null) $this->sendDate = $sendDate;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
