<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\MessageOriginType;
use Tueen\Telegram\Types\User;

/**
 * The message was originally sent by a known user.
 *
 * @link https://core.telegram.org/bots/api#messageoriginuser
 */
class MessageOriginUser extends MessageOrigin
{
    /**
     * Type of the message origin, always "user"
     */
    #[Field('type', required: true)]
    public private(set) MessageOriginType|string $type;

    /**
     * Date the message was sent originally in Unix time
     */
    #[Field('date', required: true)]
    public private(set) int $date;

    /**
     * User that sent the message originally
     */
    #[Field('sender_user', required: true)]
    public private(set) User $senderUser;

}
