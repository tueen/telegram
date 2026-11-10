<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents an incoming query from a widget in a message sent by the bot or via the bot. Use answerWidgetQuery to answer the query. Each user can send at most 5 queries per 5 seconds to the bot from all its widgets.
 *
 * @link https://core.telegram.org/bots/api#widgetquery
 */
class WidgetQuery extends Type
{
    /**
     * Unique identifier for this query
     */
    #[Field('id', required: true)]
    private(set) ?string $id = null;

    /**
     * Sender
     */
    #[Field('from', required: true)]
    private(set) ?User $from = null;

    /**
     * Optional. Chat with the message containing the widget; omitted if the widget is in an inline message
     */
    #[Field('chat', required: false)]
    private(set) ?Chat $chat = null;

    /**
     * Optional. Identifier of the message containing the widget; omitted if the widget is in an inline message
     */
    #[Field('message_id', required: false)]
    private(set) ?int $messageId = null;

    /**
     * Optional. Identifier of the inline message containing the widget
     */
    #[Field('inline_message_id', required: false)]
    private(set) ?string $inlineMessageId = null;

    /**
     * Global identifier, uniquely corresponding to the chat to which the message with the widget was sent
     */
    #[Field('chat_instance', required: true)]
    private(set) ?string $chatInstance = null;

    /**
     * Optional. For widgets in inline messages, type of the chat to which the message was sent. Can be either "sender" for a private chat with the inline query sender, "private", "group", "supergroup", or "channel".
     */
    #[Field('chat_type', required: false)]
    private(set) ?string $chatType = null;

    /**
     * Type of the query
     */
    #[Field('type', required: true)]
    private(set) ?WidgetQueryType $type = null;

    /**
     * Data of the query, 1-64 bytes
     */
    #[Field('data', required: true)]
    private(set) ?string $data = null;

    /**
     * Optional. Unique identifier of the shared widget, which sent the query; omitted if the widget isn't shared
     */
    #[Field('widget_id', required: false)]
    private(set) ?string $widgetId = null;

}
