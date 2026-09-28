<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
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
    public int|string|null $chatId = null;

    /**
     * Custom description for the verification; 0-70 characters. Must be empty if the organization isn't allowed to provide a custom verification description.
     */
    #[Field('custom_description', required: false)]
    public ?string $customDescription = null;

    public function __construct(
        int|string|null $chatId = null,
        ?string $customDescription = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($customDescription !== null) $this->customDescription = $customDescription;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
