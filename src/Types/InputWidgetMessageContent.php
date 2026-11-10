<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\ParseMode;

/**
 * Represents the content of a message with an HTML widget to be sent as the result of an inline query. Currently, widgets can't be sent in response to inline queries in the production environment.
 *
 * @link https://core.telegram.org/bots/api#inputwidgetmessagecontent
 */
class InputWidgetMessageContent extends InputMessageContent
{
    /**
     * HTML code of the widget, 1-32768 bytes. The code must not exceed 4096 bytes when compressed by zlib with the maximum compression level.
     */
    #[Field('html', required: true)]
    private(set) ?string $html = null;

    /**
     * Optional. Width of the widget in logical pixels; omit if the widget must occupy the whole width of the message bubble. The width is clamped by the app to the range from 200 to the maximum width of the message bubble.
     */
    #[Field('width', required: false)]
    private(set) ?int $width = null;

    /**
     * Optional. Height of the widget in logical pixels; omit if the height of the widget isn't fixed. The height is clamped by the app to the range 50-600.
     */
    #[Field('height', required: false)]
    private(set) ?int $height = null;

    /**
     * Optional. Public data of the widget, 0-1024 bytes, which will be available wherever the widget is shown. If the widget is shared, then all messages with it will have the same public data.
     */
    #[Field('public_data', required: false)]
    private(set) ?string $publicData = null;

    /**
     * Optional. Data of the widget, which is specific to the message, 0-1024 bytes
     */
    #[Field('chat_data', required: false)]
    private(set) ?string $chatData = null;

    /**
     * Optional. Widget caption, 0-1024 characters after entities parsing
     */
    #[Field('caption', required: false)]
    private(set) ?string $caption = null;

    /**
     * Optional. Mode for parsing entities in the widget caption. See formatting options for more details.
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
     * Optional. Pass True if the caption must be shown above the widget
     */
    #[Field('show_caption_above_media', required: false)]
    private(set) ?bool $showCaptionAboveMedia = null;

    /**
     * Optional. Pass True to allow the widget to send queries to the bot
     */
    #[Field('allow_queries', required: false)]
    private(set) ?bool $allowQueries = null;

    /**
     * Optional. Pass True to create a shared widget. Each message sent from the result creates a separate shared widget, whose identifier is received by the bot only in the field widget_id of queries from the widget. To send an existing shared widget in response to an inline query, use InputSharedWidgetMessageContent instead.
     */
    #[Field('is_shared', required: false)]
    private(set) ?bool $isShared = null;

}
