<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;

/**
 * Use this method to generate a new primary invite link for a chat; any previously generated primary link is revoked. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns the new invite link as String on success.
 *
 * @link https://core.telegram.org/bots/api#exportchatinvitelink
 */
#[ApiMethod('exportChatInviteLink', 'POST')]
#[ReturnType(Type::class, isArray: false)]
class ExportChatInviteLink extends Method
{
    /**
     * Unique identifier for the target chat or username of the target channel in the format @username
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
