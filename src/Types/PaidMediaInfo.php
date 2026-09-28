<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Describes the paid media added to a message.
 *
 * @link https://core.telegram.org/bots/api#paidmediainfo
 */
class PaidMediaInfo extends Type
{
    /**
     * The number of Telegram Stars that must be paid to buy access to the media
     */
    #[Field('star_count', required: true)]
    private(set) int $starCount;

    /**
     * Information about the paid media
     * @var PaidMedia[]|null
     */
    #[Field('paid_media', required: true)]
    #[ArrayOf(PaidMedia::class)]
    private(set) array $paidMedia;

}
