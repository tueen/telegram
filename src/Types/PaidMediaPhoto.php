<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\PaidMediaType;

/**
 * The paid media is a photo.
 *
 * @link https://core.telegram.org/bots/api#paidmediaphoto
 */
class PaidMediaPhoto extends PaidMedia
{
    /**
     * Type of the paid media, always "photo"
     */
    #[Field('type', required: true)]
    private(set) PaidMediaType|string|null $type = null;

    /**
     * The photo
     * @var PhotoSize[]|null
     */
    #[Field('photo', required: true)]
    #[ArrayOf(PhotoSize::class)]
    private(set) ?array $photo = null;

}
