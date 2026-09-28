<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\BotKickedException;
use Tueen\Telegram\Exceptions\ChatNotFoundException;
use Tueen\Telegram\Exceptions\NotEnoughRightsException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\ChatInviteLink;

/**
 * Use this method to revoke an invite link created by the bot. If the primary link is revoked, a new link is automatically generated. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns the revoked invite link as ChatInviteLink object.
 *
 * @link https://core.telegram.org/bots/api#revokechatinvitelink
 *
 * @throws ChatNotFoundException
 * @throws NotEnoughRightsException
 * @throws BotKickedException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('revokeChatInviteLink', 'POST')]
#[ReturnType(ChatInviteLink::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::ChatNotFound, TelegramErrorCode::NotEnoughRights, TelegramErrorCode::BotKicked, TelegramErrorCode::FloodWait])]
class RevokeChatInviteLink extends Method
{
    /**
     * Unique identifier of the target chat or username of the target channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * The invite link to revoke
     */
    #[Field('invite_link', required: true)]
    public ?string $inviteLink = null;

    public function __construct(
        int|string|null $chatId = null,
        ?string $inviteLink = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($inviteLink !== null) $this->inviteLink = $inviteLink;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
