<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\ChatFullInfo;

/**
 * Use this method to get up-to-date information about the chat. Returns a ChatFullInfo object on success.
 *
 * @link https://core.telegram.org/bots/api#getchat
 */
#[ApiMethod('getChat', 'POST')]
#[ReturnType(ChatFullInfo::class, isArray: false)]
class GetChat extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup or channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    public function __construct(
        int|string $chatId,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
