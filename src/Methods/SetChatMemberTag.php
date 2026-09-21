<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;

/**
 * Use this method to set a tag for a regular member in a group or a supergroup. The bot must be an administrator in the chat for this to work and must have the can_manage_tags administrator right. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setchatmembertag
 */
#[ApiMethod('setChatMemberTag', 'POST')]
#[ReturnType(Type::class, isArray: false)]
class SetChatMemberTag extends Method
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
     * New tag for the member; 0-16 characters, emoji are not allowed
     */
    #[Field('tag', required: false)]
    public ?string $tag = null;

    public function __construct(
        int|string $chatId,
        int $userId,
        ?string $tag = null
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($userId !== null) $this->userId = $userId;
        if ($tag !== null) $this->tag = $tag;
    }

    public static function make(
        int|string $chatId,
        int $userId,
        ?string $tag = null
    ): static
    {
        return new static($chatId, $userId, $tag);
    }
}
