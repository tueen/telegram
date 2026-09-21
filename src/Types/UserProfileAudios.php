<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object represents the audios displayed on a user's profile.
 *
 * @link https://core.telegram.org/bots/api#userprofileaudios
 */
class UserProfileAudios extends Type
{
    /**
     * Total number of profile audios for the target user
     */
    #[Field('total_count', required: true)]
    public private(set) int $totalCount;

    /**
     * Requested profile audios
     * @var Audio[]|null
     */
    #[Field('audios', required: true)]
    #[ArrayOf(Audio::class)]
    public private(set) array $audios;

}
