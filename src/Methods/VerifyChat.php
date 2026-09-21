<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Verifies a chat on behalf of the organization which is represented by the bot. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#verifychat
 */
#[ApiMethod('verifyChat', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class VerifyChat extends Method
{
    /**
     * Unique identifier for the target chat or username of the target bot, supergroup or channel in the format @username. Channel direct messages chats can't be verified.
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * Custom description for the verification; 0-70 characters. Must be empty if the organization isn't allowed to provide a custom verification description.
     */
    #[Field('custom_description', required: false)]
    public ?string $customDescription = null;

    public function __construct(
        int|string $chatId,
        ?string $customDescription = null
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($customDescription !== null) $this->customDescription = $customDescription;
    }

    public static function make(
        int|string $chatId,
        ?string $customDescription = null
    ): static
    {
        return new static($chatId, $customDescription);
    }
}
