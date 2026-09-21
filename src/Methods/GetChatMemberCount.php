<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\IntegerResult;

/**
 * Use this method to get the number of members in a chat. Returns Integer on success.
 *
 * @link https://core.telegram.org/bots/api#getchatmembercount
 */
#[ApiMethod('getChatMemberCount', 'POST')]
#[ReturnType(IntegerResult::class, isArray: false)]
class GetChatMemberCount extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup or channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    public function __construct(
        int|string $chatId
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
    }

    public static function make(
        int|string $chatId
    ): static
    {
        return new static($chatId);
    }
}
