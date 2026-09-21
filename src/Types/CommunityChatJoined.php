<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\Community;

/**
 * Describes a service message about a chat being joined by a user from a community.
 *
 * @link https://core.telegram.org/bots/api#communitychatjoined
 */
class CommunityChatJoined extends Type
{
    /**
     * The community from which the chat was joined
     */
    #[Field('community', required: true)]
    public private(set) Community $community;

}
