<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\ParseMode;

/**
 * Represents the content of a text message to be sent as the result of an inline query.
 *
 * @link https://core.telegram.org/bots/api#inputtextmessagecontent
 */
class InputTextMessageContent extends InputMessageContent
{
    /**
     * Text of the message to be sent, 1-4096 characters
     */
    #[Field('message_text', required: true)]
    private(set) string $messageText;

    /**
     * Optional. Mode for parsing entities in the message text. See formatting options for more details.
     */
    #[Field('parse_mode', required: false)]
    private(set) ParseMode|string|null $parseMode = null;

    /**
     * Optional. List of special entities that appear in message text, which can be specified instead of parse_mode
     * @var MessageEntity[]|null
     */
    #[Field('entities', required: false)]
    #[ArrayOf(MessageEntity::class)]
    private(set) ?array $entities = null;

    /**
     * Optional. Link preview generation options for the message
     */
    #[Field('link_preview_options', required: false)]
    private(set) ?LinkPreviewOptions $linkPreviewOptions = null;

}
