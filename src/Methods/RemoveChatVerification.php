<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;

/**
 * Removes verification from a chat that is currently verified on behalf of the organization represented by the bot. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#removechatverification
 */
#[ApiMethod('removeChatVerification', 'POST')]
#[ReturnType(Type::class, isArray: false)]
class RemoveChatVerification extends Method
{
    /**
     * Unique identifier for the target chat or username of the target bot or channel in the format @username
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
