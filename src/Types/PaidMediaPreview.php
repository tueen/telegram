<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\PaidMediaType;

/**
 * The paid media isn't available before the payment.
 *
 * @link https://core.telegram.org/bots/api#paidmediapreview
 */
class PaidMediaPreview extends PaidMedia
{
    /**
     * Type of the paid media, always "preview"
     */
    #[Field('type', required: true)]
    private(set) PaidMediaType|string $type;

    /**
     * Optional. Media width as defined by the sender
     */
    #[Field('width', required: false)]
    private(set) ?int $width = null;

    /**
     * Optional. Media height as defined by the sender
     */
    #[Field('height', required: false)]
    private(set) ?int $height = null;

    /**
     * Optional. Duration of the media in seconds as defined by the sender
     */
    #[Field('duration', required: false)]
    private(set) ?int $duration = null;

}
