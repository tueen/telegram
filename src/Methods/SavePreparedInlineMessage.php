<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\PreparedInlineMessage;
use Tueen\Telegram\Types\InlineQueryResult;

/**
 * Stores a message that can be sent by a user of a Mini App. Returns a PreparedInlineMessage object.
 *
 * @link https://core.telegram.org/bots/api#savepreparedinlinemessage
 */
#[ApiMethod('savePreparedInlineMessage', 'POST')]
#[ReturnType(PreparedInlineMessage::class, isArray: false)]
class SavePreparedInlineMessage extends Method
{
    /**
     * Unique identifier of the target user that can use the prepared message
     */
    #[Field('user_id', required: true)]
    public int $userId;

    /**
     * A JSON-serialized object describing the message to be sent
     */
    #[Field('result', required: true)]
    public InlineQueryResult $result;

    /**
     * Pass True if the message can be sent to private chats with users
     */
    #[Field('allow_user_chats', required: false)]
    public ?bool $allowUserChats = null;

    /**
     * Pass True if the message can be sent to private chats with bots
     */
    #[Field('allow_bot_chats', required: false)]
    public ?bool $allowBotChats = null;

    /**
     * Pass True if the message can be sent to group and supergroup chats
     */
    #[Field('allow_group_chats', required: false)]
    public ?bool $allowGroupChats = null;

    /**
     * Pass True if the message can be sent to channel chats
     */
    #[Field('allow_channel_chats', required: false)]
    public ?bool $allowChannelChats = null;

    public function __construct(
        int $userId,
        InlineQueryResult $result,
        ?bool $allowUserChats = null,
        ?bool $allowBotChats = null,
        ?bool $allowGroupChats = null,
        ?bool $allowChannelChats = null,
        mixed ...$extra
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($result !== null) $this->result = $result;
        if ($allowUserChats !== null) $this->allowUserChats = $allowUserChats;
        if ($allowBotChats !== null) $this->allowBotChats = $allowBotChats;
        if ($allowGroupChats !== null) $this->allowGroupChats = $allowGroupChats;
        if ($allowChannelChats !== null) $this->allowChannelChats = $allowChannelChats;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
