<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to edit public data of a shared widget created by the bot. A shared widget can be edited at most once per second. On success, True is returned.
 *
 * @link https://core.telegram.org/bots/api#editsharedwidgetdata
 */
#[ApiMethod('editSharedWidgetData', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class EditSharedWidgetData extends Method
{
    /**
     * Unique identifier of the shared widget
     */
    #[Field('widget_id', required: true)]
    public string $widgetId;

    /**
     * New public data of the widget, 0-1024 bytes; pass an empty string to remove the public data
     */
    #[Field('public_data', required: true)]
    public string $publicData;

    /**
     * The maximum amount of time in seconds that the public data of the widget may be cached client-side; 5-3600. If omitted, then the current value is kept, which is 30 by default.
     */
    #[Field('cache_time', required: false)]
    public ?int $cacheTime = null;

    public function __construct(
        string $widgetId,
        string $publicData,
        ?int $cacheTime = null,
        mixed ...$extra
    )
    {
        $this->widgetId = $widgetId;
        $this->publicData = $publicData;
        if ($cacheTime !== null) $this->cacheTime = $cacheTime;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
