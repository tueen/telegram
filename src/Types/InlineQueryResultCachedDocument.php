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
 * Represents a link to a file stored on the Telegram servers. By default, this file will be sent by the user with an optional caption. Alternatively, you can use input_message_content to send a message with the specified content instead of the file.
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultcacheddocument
 */
class InlineQueryResultCachedDocument extends InlineQueryResult
{
    /**
     * Type of the result, must be document
     */
    #[Field('type', required: true)]
    public private(set) string $type;

    /**
     * Unique identifier for this result, 1-64 bytes
     */
    #[Field('id', required: true)]
    public private(set) string $id;

    /**
     * Title for the result
     */
    #[Field('title', required: true)]
    public private(set) string $title;

    /**
     * A valid file identifier for the file
     */
    #[Field('document_file_id', required: true)]
    public private(set) string $documentFileId;

    /**
     * Optional. Short description of the result
     */
    #[Field('description', required: false)]
    public private(set) ?string $description = null;

    /**
     * Optional. Caption of the document to be sent, 0-1024 characters after entities parsing
     */
    #[Field('caption', required: false)]
    public private(set) ?string $caption = null;

    /**
     * Optional. Mode for parsing entities in the document caption. See formatting options for more details.
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
     * Optional. Inline keyboard attached to the message
     */
    #[Field('reply_markup', required: false)]
    public private(set) ?InlineKeyboardMarkup $replyMarkup = null;

    /**
     * Optional. Content of the message to be sent instead of the file
     */
    #[Field('input_message_content', required: false)]
    public private(set) ?InputMessageContent $inputMessageContent = null;

}
