<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to send an answer to a query from a widget. The query must be answered within 15 seconds. The answer is received only by the widget that sent the query and doesn't change data of the widget; use editMessageWidgetData or editSharedWidgetData to change it. On success, True is returned.
 *
 * @link https://core.telegram.org/bots/api#answerwidgetquery
 */
#[ApiMethod('answerWidgetQuery', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class AnswerWidgetQuery extends Method
{
    /**
     * Unique identifier for the query to be answered
     */
    #[Field('widget_query_id', required: true)]
    public ?string $widgetQueryId = null;

    /**
     * Data to be returned to the widget, 0-1024 bytes
     */
    #[Field('data', required: false)]
    public ?string $data = null;

    public function __construct(
        ?string $widgetQueryId = null,
        ?string $data = null,
        mixed ...$extra
    )
    {
        if ($widgetQueryId !== null) $this->widgetQueryId = $widgetQueryId;
        if ($data !== null) $this->data = $data;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
