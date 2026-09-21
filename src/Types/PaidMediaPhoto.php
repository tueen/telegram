<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\PaidMediaType;
use Tueen\Telegram\Types\PhotoSize;

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
    public private(set) PaidMediaType|string $type;

    /**
     * The photo
     * @var PhotoSize[]|null
     */
    #[Field('photo', required: true)]
    #[ArrayOf(PhotoSize::class)]
    public private(set) array $photo;

}
