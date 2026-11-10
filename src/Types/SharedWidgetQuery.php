<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents an incoming query from a forwarded message with a shared widget created by the bot. Use answerWidgetQuery to answer the query. Each user can send at most 5 queries per 5 seconds to the bot from all its widgets.
 *
 * @link https://core.telegram.org/bots/api#sharedwidgetquery
 */
class SharedWidgetQuery extends Type
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
     * Unique identifier of the shared widget, which sent the query
     */
    #[Field('widget_id', required: true)]
    private(set) ?string $widgetId = null;

}
