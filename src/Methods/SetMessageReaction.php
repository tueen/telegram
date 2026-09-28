<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to change the chosen reactions on a message. Service messages of some types can't be reacted to. Automatically forwarded messages from a channel to its discussion group have the same available reactions as messages in the channel. Bots can't use paid reactions. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setmessagereaction
 */
#[ApiMethod('setMessageReaction', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SetMessageReaction extends Method
{
    /**
     * Unique identifier for the target chat or username of the target bot, supergroup or channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * Identifier of the target message. If the message belongs to a media group, the reaction is set to the first non-deleted message in the group instead.
     */
    #[Field('message_id', required: true)]
    public int $messageId;

    /**
     * A JSON-serialized list of reaction types to set on the message. Currently, as non-premium users, bots can set up to one reaction per message. A custom emoji reaction can be used if it is either already present on the message or explicitly allowed by chat administrators. Paid reactions can't be used by bots.
     */
    #[Field('reaction', required: false)]
    public ?array $reaction = null;

    /**
     * Pass True to set the reaction with a big animation
     */
    #[Field('is_big', required: false)]
    public ?bool $isBig = null;

    public function __construct(
        int|string $chatId,
        int $messageId,
        ?array $reaction = null,
        ?bool $isBig = null,
        mixed ...$extra
    )
    {
        $this->chatId = $chatId;
        $this->messageId = $messageId;
        if ($reaction !== null) $this->reaction = $reaction;
        if ($isBig !== null) $this->isBig = $isBig;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
