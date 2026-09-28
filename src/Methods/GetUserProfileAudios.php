<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\UserProfileAudios;

/**
 * Use this method to get a list of profile audios for a user. Returns a UserProfileAudios object.
 *
 * @link https://core.telegram.org/bots/api#getuserprofileaudios
 */
#[ApiMethod('getUserProfileAudios', 'POST')]
#[ReturnType(UserProfileAudios::class, isArray: false)]
class GetUserProfileAudios extends Method
{
    /**
     * Unique identifier of the target user
     */
    #[Field('user_id', required: true)]
    public int $userId;

    /**
     * Sequential number of the first audio to be returned. By default, all audios are returned.
     */
    #[Field('offset', required: false)]
    public ?int $offset = null;

    /**
     * Limits the number of audios to be retrieved. Values between 1-100 are accepted. Defaults to 100.
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
        if ($userId !== null) $this->userId = $userId;
        if ($offset !== null) $this->offset = $offset;
        if ($limit !== null) $this->limit = $limit;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
