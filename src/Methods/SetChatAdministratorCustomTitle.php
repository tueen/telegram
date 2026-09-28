<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
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
    public int|string|null $chatId = null;

    /**
     * Unique identifier of the target user
     */
    #[Field('user_id', required: true)]
    public ?int $userId = null;

    /**
     * New custom title for the administrator; 0-16 characters, emoji are not allowed
     */
    #[Field('custom_title', required: true)]
    public ?string $customTitle = null;

    public function __construct(
        int|string|null $chatId = null,
        ?int $userId = null,
        ?string $customTitle = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($userId !== null) $this->userId = $userId;
        if ($customTitle !== null) $this->customTitle = $customTitle;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
