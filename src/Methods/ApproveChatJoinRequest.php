<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to approve a chat join request. The bot must be an administrator in the chat for this to work and must have the can_invite_users administrator right. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#approvechatjoinrequest
 */
#[ApiMethod('approveChatJoinRequest', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class ApproveChatJoinRequest extends Method
{
    /**
     * Unique identifier for the target chat or username of the target channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * Unique identifier of the target user
     */
    #[Field('user_id', required: true)]
    public ?int $userId = null;

    public function __construct(
        int|string|null $chatId = null,
        ?int $userId = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($userId !== null) $this->userId = $userId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
