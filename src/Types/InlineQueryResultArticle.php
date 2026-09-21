<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InlineQueryResultType;

/**
 * Represents a link to an article or web page.
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultarticle
 */
class InlineQueryResultArticle extends InlineQueryResult
{
    /**
     * Type of the result, must be article
     */
    #[Field('type', required: true)]
    public private(set) InlineQueryResultType|string $type;

    /**
     * Unique identifier for this result, 1-64 Bytes
     */
    #[Field('id', required: true)]
    public private(set) string $id;

    /**
     * Title of the result
     */
    #[Field('title', required: true)]
    public private(set) string $title;

    /**
     * Content of the message to be sent
     */
    #[Field('input_message_content', required: true)]
    public private(set) InputMessageContent $inputMessageContent;

    /**
     * Optional. Inline keyboard attached to the message
     */
    #[Field('reply_markup', required: false)]
    public private(set) ?InlineKeyboardMarkup $replyMarkup = null;

    /**
     * Optional. URL of the result
     */
    #[Field('url', required: false)]
    public private(set) ?string $url = null;

    /**
     * Optional. Short description of the result
     */
    #[Field('description', required: false)]
    public private(set) ?string $description = null;

    /**
     * Optional. Url of the thumbnail for the result
     */
    #[Field('thumbnail_url', required: false)]
    public private(set) ?string $thumbnailUrl = null;

    /**
     * Optional. Thumbnail width
     */
    #[Field('thumbnail_width', required: false)]
    public private(set) ?int $thumbnailWidth = null;

    /**
     * Optional. Thumbnail height
     */
    #[Field('thumbnail_height', required: false)]
    public private(set) ?int $thumbnailHeight = null;

}
