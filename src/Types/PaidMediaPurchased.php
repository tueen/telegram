<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\User;

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
    public private(set) User $from;

    /**
     * Bot-specified paid media payload
     */
    #[Field('paid_media_payload', required: true)]
    public private(set) string $paidMediaPayload;

}
