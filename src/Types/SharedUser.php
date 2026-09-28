<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object contains information about a user that was shared with the bot using a KeyboardButtonRequestUsers button.
 *
 * @link https://core.telegram.org/bots/api#shareduser
 */
class SharedUser extends Type
{
    /**
     * Identifier of the shared user. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so 64-bit integers or double-precision float types are safe for storing these identifiers. The bot may not have access to the user and could be unable to use this identifier, unless the user is already known to the bot by some other means.
     */
    #[Field('user_id', required: true)]
    private(set) int $userId;

    /**
     * Optional. First name of the user, if the name was requested by the bot
     */
    #[Field('first_name', required: false)]
    private(set) ?string $firstName = null;

    /**
     * Optional. Last name of the user, if the name was requested by the bot
     */
    #[Field('last_name', required: false)]
    private(set) ?string $lastName = null;

    /**
     * Optional. Username of the user, if the username was requested by the bot
     */
    #[Field('username', required: false)]
    private(set) ?string $username = null;

    /**
     * Optional. Available sizes of the chat photo, if the photo was requested by the bot
     * @var PhotoSize[]|null
     */
    #[Field('photo', required: false)]
    #[ArrayOf(PhotoSize::class)]
    private(set) ?array $photo = null;

}
