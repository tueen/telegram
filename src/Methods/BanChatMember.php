<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to ban a user in a group, a supergroup or a channel. In the case of supergroups and channels, the user will not be able to return to the chat on their own using invite links, etc., unless unbanned first. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#banchatmember
 */
#[ApiMethod('banChatMember', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class BanChatMember extends Method
{
    /**
     * Unique identifier for the target group or username of the target supergroup or channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * Unique identifier of the target user
     */
    #[Field('user_id', required: true)]
    public int $userId;

    /**
     * Date when the user will be unbanned; Unix time. If user is banned for more than 366 days or less than 30 seconds from the current time they are considered to be banned forever. Applied for supergroups and channels only.
     */
    #[Field('until_date', required: false)]
    public ?int $untilDate = null;

    /**
     * Pass True to delete all messages from the chat for the user that is being removed. If False, the user will be able to see messages in the group that were sent before the user was removed. Always True for supergroups and channels.
     */
    #[Field('revoke_messages', required: false)]
    public ?bool $revokeMessages = null;

    public function __construct(
        int|string $chatId,
        int $userId,
        ?int $untilDate = null,
        ?bool $revokeMessages = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($userId !== null) $this->userId = $userId;
        if ($untilDate !== null) $this->untilDate = $untilDate;
        if ($revokeMessages !== null) $this->revokeMessages = $revokeMessages;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
