<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to unban a previously banned user in a supergroup or channel. The user will not return to the group or channel automatically, but will be able to join via link, etc. The bot must be an administrator for this to work. By default, this method guarantees that after the call the user is not a member of the chat, but will be able to join it. So if the user is a member of the chat they will also be removed from the chat. If you don't want this, use the parameter only_if_banned. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#unbanchatmember
 */
#[ApiMethod('unbanChatMember', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class UnbanChatMember extends Method
{
    /**
     * Unique identifier for the target group or username of the target supergroup or channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * Unique identifier of the target user
     */
    #[Field('user_id', required: true)]
    public ?int $userId = null;

    /**
     * Do nothing if the user is not banned
     */
    #[Field('only_if_banned', required: false)]
    public ?bool $onlyIfBanned = null;

    public function __construct(
        int|string|null $chatId = null,
        ?int $userId = null,
        ?bool $onlyIfBanned = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($userId !== null) $this->userId = $userId;
        if ($onlyIfBanned !== null) $this->onlyIfBanned = $onlyIfBanned;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
