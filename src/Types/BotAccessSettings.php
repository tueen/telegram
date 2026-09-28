<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object describes the access settings of a bot.
 *
 * @link https://core.telegram.org/bots/api#botaccesssettings
 */
class BotAccessSettings extends Type
{
    /**
     * True, if only selected users can access the bot. The bot's owner can always access it.
     */
    #[Field('is_access_restricted', required: true)]
    private(set) bool $isAccessRestricted;

    /**
     * Optional. The list of other users who have access to the bot if the access is restricted
     * @var User[]|null
     */
    #[Field('added_users', required: false)]
    #[ArrayOf(User::class)]
    private(set) ?array $addedUsers = null;

}
