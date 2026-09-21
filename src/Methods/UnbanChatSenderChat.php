<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to unban a previously banned channel chat in a supergroup or channel. The bot must be an administrator for this to work and must have the appropriate administrator rights. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#unbanchatsenderchat
 */
#[ApiMethod('unbanChatSenderChat', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class UnbanChatSenderChat extends Method
{
    /**
     * Unique identifier for the target chat or username of the target channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * Unique identifier of the target sender chat
     */
    #[Field('sender_chat_id', required: true)]
    public int $senderChatId;

    public function __construct(
        int|string $chatId,
        int $senderChatId
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($senderChatId !== null) $this->senderChatId = $senderChatId;
    }
}
