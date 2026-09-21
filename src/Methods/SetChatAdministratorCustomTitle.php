<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to set a custom title for an administrator in a supergroup promoted by the bot. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setchatadministratorcustomtitle
 */
#[ApiMethod('setChatAdministratorCustomTitle', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SetChatAdministratorCustomTitle extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * Unique identifier of the target user
     */
    #[Field('user_id', required: true)]
    public int $userId;

    /**
     * New custom title for the administrator; 0-16 characters, emoji are not allowed
     */
    #[Field('custom_title', required: true)]
    public string $customTitle;

    public function __construct(
        int|string $chatId,
        int $userId,
        string $customTitle
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($userId !== null) $this->userId = $userId;
        if ($customTitle !== null) $this->customTitle = $customTitle;
    }
}
