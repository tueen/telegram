<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\UserProfilePhotos;

/**
 * Use this method to get a list of profile pictures for a user. Returns a UserProfilePhotos object.
 *
 * @link https://core.telegram.org/bots/api#getuserprofilephotos
 */
#[ApiMethod('getUserProfilePhotos', 'POST')]
#[ReturnType(UserProfilePhotos::class, isArray: false)]
class GetUserProfilePhotos extends Method
{
    /**
     * Unique identifier of the target user
     */
    #[Field('user_id', required: true)]
    public int $userId;

    /**
     * Sequential number of the first photo to be returned. By default, all photos are returned.
     */
    #[Field('offset', required: false)]
    public ?int $offset = null;

    /**
     * Limits the number of photos to be retrieved. Values between 1-100 are accepted. Defaults to 100.
     */
    #[Field('limit', required: false)]
    public ?int $limit = null;

    public function __construct(
        int $userId,
        ?int $offset = null,
        ?int $limit = null,
        mixed ...$extra
    )
    {
        $this->userId = $userId;
        if ($offset !== null) $this->offset = $offset;
        if ($limit !== null) $this->limit = $limit;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
