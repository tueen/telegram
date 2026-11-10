<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object describes an HTML widget.
 *
 * @link https://core.telegram.org/bots/api#widget
 */
class Widget extends Type
{
    /**
     * Optional. Unique identifier of the shared widget, which can be used to send the widget again using the method sendSharedWidget; omitted if the widget isn't shared
     */
    #[Field('id', required: false)]
    private(set) ?string $id = null;

    /**
     * Optional. Width of the widget in logical pixels; omitted if the widget must occupy the whole width of the message bubble
     */
    #[Field('width', required: false)]
    private(set) ?int $width = null;

    /**
     * Optional. Height of the widget in logical pixels; omitted if the height of the widget isn't fixed
     */
    #[Field('height', required: false)]
    private(set) ?int $height = null;

    /**
     * HTML code of the widget
     */
    #[Field('html', required: true)]
    private(set) ?string $html = null;

    /**
     * Optional. Public data of the widget, which is available wherever the widget is shown. All messages with a shared widget have the same public data.
     */
    #[Field('public_data', required: false)]
    private(set) ?string $publicData = null;

    /**
     * Optional. Data of the widget, which is specific to the message
     */
    #[Field('chat_data', required: false)]
    private(set) ?string $chatData = null;

}
