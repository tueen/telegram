<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to change the title of a chat. Titles can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setchattitle
 */
#[ApiMethod('setChatTitle', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SetChatTitle extends Method
{
    /**
     * Unique identifier for the target chat or username of the target channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * New chat title, 1-128 characters
     */
    #[Field('title', required: true)]
    public string $title;

    public function __construct(
        int|string $chatId,
        string $title,
        mixed ...$extra
    )
    {
        $this->chatId = $chatId;
        $this->title = $title;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
