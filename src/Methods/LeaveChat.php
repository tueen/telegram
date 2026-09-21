<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method for your bot to leave a group, supergroup or channel. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#leavechat
 */
#[ApiMethod('leaveChat', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class LeaveChat extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup or channel in the format @username. Channel direct messages chats aren't supported; leave the corresponding channel instead.
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
