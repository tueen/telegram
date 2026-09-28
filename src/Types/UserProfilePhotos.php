<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object represent a user's profile pictures.
 *
 * @link https://core.telegram.org/bots/api#userprofilephotos
 */
class UserProfilePhotos extends Type
{
    /**
     * Total number of profile pictures the target user has
     */
    #[Field('total_count', required: true)]
    private(set) int $totalCount;

    /**
     * Requested profile pictures (in up to 4 sizes each)
     * @var PhotoSize[]|null
     */
    #[Field('photos', required: true)]
    #[ArrayOf(PhotoSize::class)]
    private(set) array $photos;

}
