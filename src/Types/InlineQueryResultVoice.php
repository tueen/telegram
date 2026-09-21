<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\MessageEntity;
use Tueen\Telegram\Types\InlineKeyboardMarkup;
use Tueen\Telegram\Types\InputMessageContent;

/**
 * Represents a link to a voice recording in an .OGG container encoded with OPUS. By default, this voice recording will be sent by the user. Alternatively, you can use input_message_content to send a message with the specified content instead of the the voice message.
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultvoice
 */
class InlineQueryResultVoice extends InlineQueryResult
{
    /**
     * Type of the result, must be voice
     */
    #[Field('type', required: true)]
    public private(set) string $type;

    /**
     * Unique identifier for this result, 1-64 bytes
     */
    #[Field('id', required: true)]
    public private(set) string $id;

    /**
     * A valid URL for the voice recording
     */
    #[Field('voice_url', required: true)]
    public private(set) string $voiceUrl;

    /**
     * Recording title
     */
    #[Field('title', required: true)]
    public private(set) string $title;

    /**
     * Optional. Caption, 0-1024 characters after entities parsing
     */
    #[Field('caption', required: false)]
    public private(set) ?string $caption = null;

    /**
     * Optional. Mode for parsing entities in the voice message caption. See formatting options for more details.
     */
    #[Field('parse_mode', required: false)]
    public private(set) ?string $parseMode = null;

    /**
     * Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode
     * @var MessageEntity[]|null
     */
    #[Field('caption_entities', required: false)]
    #[ArrayOf(MessageEntity::class)]
    public private(set) ?array $captionEntities = null;

    /**
     * Optional. Recording duration in seconds
     */
    #[Field('voice_duration', required: false)]
    public private(set) ?int $voiceDuration = null;

    /**
     * Optional. Inline keyboard attached to the message
     */
    #[Field('reply_markup', required: false)]
    public private(set) ?InlineKeyboardMarkup $replyMarkup = null;

    /**
     * Optional. Content of the message to be sent instead of the voice recording
     */
    #[Field('input_message_content', required: false)]
    public private(set) ?InputMessageContent $inputMessageContent = null;

}
