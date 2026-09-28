<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a list of boosts added to a chat by a user.
 *
 * @link https://core.telegram.org/bots/api#userchatboosts
 */
class UserChatBoosts extends Type
{
    /**
     * The list of boosts added to the chat by the user
     * @var ChatBoost[]|null
     */
    #[Field('boosts', required: true)]
    #[ArrayOf(ChatBoost::class)]
    private(set) ?array $boosts = null;

}
