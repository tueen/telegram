<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Describes reply parameters for the message that is being sent.
 *
 * @link https://core.telegram.org/bots/api#replyparameters
 */
class ReplyParameters extends Type
{
    /**
     * Optional. Identifier of the message that will be replied to in the current chat, or in the chat chat_id if it is specified. Required if ephemeral_message_id isn't specified.
     */
    #[Field('message_id', required: false)]
    public private(set) ?int $messageId = null;

    /**
     * Optional. If the message to be replied to is from a different chat, unique identifier for the chat or username of the bot, supergroup or channel in the format @username. Not supported for messages sent on behalf of a business account, messages from channel direct messages chats and ephemeral messages.
     */
    #[Field('chat_id', required: false)]
    public private(set) int|string|null $chatId = null;

    /**
     * Optional. Identifier of the incoming ephemeral message that will be replied to in the current chat. A reply to an ephemeral message must itself be an ephemeral message. An ephemeral message may only be replied to within 15 seconds of being sent. Required if message_id isn't specified.
     */
    #[Field('ephemeral_message_id', required: false)]
    public private(set) ?int $ephemeralMessageId = null;

    /**
     * Optional. Pass True if the message should be sent even if the specified message to be replied to is not found. Always False for replies in another chat or forum topic, and sent ephemeral messages. Always True for messages sent on behalf of a business account.
     */
    #[Field('allow_sending_without_reply', required: false)]
    public private(set) ?bool $allowSendingWithoutReply = null;

    /**
     * Optional. Quoted part of the message to be replied to; 0-1024 characters after entities parsing. The quote must be an exact substring of the message to be replied to, including bold, italic, underline, strikethrough, spoiler, custom_emoji, and date_time entities. The message will fail to send if the quote isn't found in the original message. Ignored for ephemeral messages.
     */
    #[Field('quote', required: false)]
    public private(set) ?string $quote = null;

    /**
     * Optional. Mode for parsing entities in the quote. See formatting options for more details.
     */
    #[Field('quote_parse_mode', required: false)]
    public private(set) ?string $quoteParseMode = null;

    /**
     * Optional. A JSON-serialized list of special entities that appear in the quote. It can be specified instead of quote_parse_mode.
     * @var MessageEntity[]|null
     */
    #[Field('quote_entities', required: false)]
    #[ArrayOf(MessageEntity::class)]
    public private(set) ?array $quoteEntities = null;

    /**
     * Optional. Position of the quote in the original message in UTF-16 code units
     */
    #[Field('quote_position', required: false)]
    public private(set) ?int $quotePosition = null;

    /**
     * Optional. Identifier of the specific checklist task to be replied to
     */
    #[Field('checklist_task_id', required: false)]
    public private(set) ?int $checklistTaskId = null;

    /**
     * Optional. Persistent identifier of the specific poll option to be replied to
     */
    #[Field('poll_option_id', required: false)]
    public private(set) ?string $pollOptionId = null;

}
