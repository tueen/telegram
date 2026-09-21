<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\Community;

/**
 * Describes a service message about a chat or a bot being added to a community.
 *
 * @link https://core.telegram.org/bots/api#communitychatadded
 */
class CommunityChatAdded extends Type
{
    /**
     * The new community to which the chat or the bot belongs
     */
    #[Field('community', required: true)]
    public private(set) Community $community;

}
