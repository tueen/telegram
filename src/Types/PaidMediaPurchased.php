<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object contains information about a paid media purchase.
 *
 * @link https://core.telegram.org/bots/api#paidmediapurchased
 */
class PaidMediaPurchased extends Type
{
    /**
     * User who purchased the media
     */
    #[Field('from', required: true)]
    private(set) ?User $from = null;

    /**
     * Bot-specified paid media payload
     */
    #[Field('paid_media_payload', required: true)]
    private(set) ?string $paidMediaPayload = null;

}
