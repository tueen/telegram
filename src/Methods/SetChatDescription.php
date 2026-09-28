<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to change the description of a group, a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setchatdescription
 */
#[ApiMethod('setChatDescription', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SetChatDescription extends Method
{
    /**
     * Unique identifier for the target chat or username of the target channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * New chat description, 0-255 characters
     */
    #[Field('description', required: false)]
    public ?string $description = null;

    public function __construct(
        int|string $chatId,
        ?string $description = null,
        mixed ...$extra
    )
    {
        $this->chatId = $chatId;
        if ($description !== null) $this->description = $description;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
