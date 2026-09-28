<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Message;

/**
 * Use this method to get the last messages from the personal chat (i.e., the chat currently added to their profile) of a given user. On success, an Array of Message objects is returned.
 *
 * @link https://core.telegram.org/bots/api#getuserpersonalchatmessages
 */
#[ApiMethod('getUserPersonalChatMessages', 'POST')]
#[ReturnType(Message::class, isArray: true)]
class GetUserPersonalChatMessages extends Method
{
    /**
     * Unique identifier for the target user
     */
    #[Field('user_id', required: true)]
    public int $userId;

    /**
     * The maximum number of messages to return; 1-20
     */
    #[Field('limit', required: true)]
    public int $limit;

    public function __construct(
        int $userId,
        int $limit,
        mixed ...$extra
    )
    {
        $this->userId = $userId;
        $this->limit = $limit;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
