<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;

/**
 * Use this method to edit the name of the 'General' topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#editgeneralforumtopic
 */
#[ApiMethod('editGeneralForumTopic', 'POST')]
#[ReturnType(Type::class, isArray: false)]
class EditGeneralForumTopic extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * New topic name, 1-128 characters
     */
    #[Field('name', required: true)]
    public string $name;

    public function __construct(
        int|string $chatId,
        string $name
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($name !== null) $this->name = $name;
    }

    public static function make(
        int|string $chatId,
        string $name
    ): static
    {
        return new static($chatId, $name);
    }
}
