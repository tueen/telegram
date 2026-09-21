<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\ChatMember;

/**
 * Use this method to get a list of administrators in a chat. Returns an Array of ChatMember objects.
 *
 * @link https://core.telegram.org/bots/api#getchatadministrators
 */
#[ApiMethod('getChatAdministrators', 'POST')]
#[ReturnType(ChatMember::class, isArray: true)]
class GetChatAdministrators extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup or channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * Pass True to additionally receive all bots that are administrators of the chat. By default, bots other than the current bot are omitted.
     */
    #[Field('return_bots', required: false)]
    public ?bool $returnBots = null;

    public function __construct(
        int|string $chatId,
        ?bool $returnBots = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($returnBots !== null) $this->returnBots = $returnBots;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
