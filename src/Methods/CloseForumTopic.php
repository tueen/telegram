<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\ChatNotFoundException;
use Tueen\Telegram\Exceptions\NotEnoughRightsException;
use Tueen\Telegram\Exceptions\TopicNotModifiedException;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to close an open topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights, unless it is the creator of the topic. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#closeforumtopic
 *
 * @throws ChatNotFoundException
 * @throws TopicNotModifiedException
 * @throws NotEnoughRightsException
 * @throws ApiException
 */
#[ApiMethod('closeForumTopic', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::ChatNotFound, TelegramErrorCode::TopicNotModified, TelegramErrorCode::NotEnoughRights])]
class CloseForumTopic extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * Unique identifier for the target message thread of the forum topic
     */
    #[Field('message_thread_id', required: true)]
    public ?int $messageThreadId = null;

    public function __construct(
        int|string|null $chatId = null,
        ?int $messageThreadId = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($messageThreadId !== null) $this->messageThreadId = $messageThreadId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
