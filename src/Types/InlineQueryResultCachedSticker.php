<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InlineQueryResultType;

/**
 * Represents a link to a sticker stored on the Telegram servers. By default, this sticker will be sent by the user. Alternatively, you can use input_message_content to send a message with the specified content instead of the sticker.
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultcachedsticker
 */
class InlineQueryResultCachedSticker extends InlineQueryResult
{
    /**
     * Type of the result, must be sticker
     */
    #[Field('type', required: true)]
    private(set) InlineQueryResultType|string|null $type = null;

    /**
     * Unique identifier for this result, 1-64 bytes
     */
    #[Field('id', required: true)]
    private(set) ?string $id = null;

    /**
     * A valid file identifier of the sticker
     */
    #[Field('sticker_file_id', required: true)]
    private(set) ?string $stickerFileId = null;

    /**
     * Optional. Inline keyboard attached to the message
     */
    #[Field('reply_markup', required: false)]
    private(set) ?InlineKeyboardMarkup $replyMarkup = null;

    /**
     * Optional. Content of the message to be sent instead of the sticker
     */
    #[Field('input_message_content', required: false)]
    private(set) ?InputMessageContent $inputMessageContent = null;

}
