<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object contains information about the users whose identifiers were shared with the bot using a KeyboardButtonRequestUsers button.
 *
 * @link https://core.telegram.org/bots/api#usersshared
 */
class UsersShared extends Type
{
    /**
     * Identifier of the request
     */
    #[Field('request_id', required: true)]
    private(set) int $requestId;

    /**
     * Information about users shared with the bot
     * @var SharedUser[]|null
     */
    #[Field('users', required: true)]
    #[ArrayOf(SharedUser::class)]
    private(set) array $users;

}
