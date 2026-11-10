<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\ParseMode;

/**
 * Represents the content of a message with a shared widget to be sent as the result of an inline query. Currently, widgets can't be sent in response to inline queries in the production environment.
 *
 * @link https://core.telegram.org/bots/api#inputsharedwidgetmessagecontent
 */
class InputSharedWidgetMessageContent extends InputMessageContent
{
    /**
     * Unique identifier of the shared widget created by the bot
     */
    #[Field('widget_id', required: true)]
    private(set) ?string $widgetId = null;

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

}
