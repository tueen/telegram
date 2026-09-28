<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InlineQueryResultType;
use Tueen\Telegram\Enums\ParseMode;

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
    private(set) InlineQueryResultType|string|null $type = null;

    /**
     * Unique identifier for this result, 1-64 bytes
     */
    #[Field('id', required: true)]
    private(set) ?string $id = null;

    /**
     * A valid URL for the voice recording
     */
    #[Field('voice_url', required: true)]
    private(set) ?string $voiceUrl = null;

    /**
     * Recording title
     */
    #[Field('title', required: true)]
    private(set) ?string $title = null;

    /**
     * Optional. Caption, 0-1024 characters after entities parsing
     */
    #[Field('caption', required: false)]
    private(set) ?string $caption = null;

    /**
     * Optional. Mode for parsing entities in the voice message caption. See formatting options for more details.
     */
    #[Field('parse_mode', required: false)]
    private(set) ParseMode|string|null $parseMode = null;

    /**
     * Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode
     * @var MessageEntity[]|null
     */
    #[Field('caption_entities', required: false)]
    #[ArrayOf(MessageEntity::class)]
    private(set) ?array $captionEntities = null;

    /**
     * Optional. Recording duration in seconds
     */
    #[Field('voice_duration', required: false)]
    private(set) ?int $voiceDuration = null;

    /**
     * Optional. Inline keyboard attached to the message
     */
    #[Field('reply_markup', required: false)]
    private(set) ?InlineKeyboardMarkup $replyMarkup = null;

    /**
     * Optional. Content of the message to be sent instead of the voice recording
     */
    #[Field('input_message_content', required: false)]
    private(set) ?InputMessageContent $inputMessageContent = null;

}
