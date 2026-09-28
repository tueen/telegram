<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\MessageOriginType;

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
    private(set) MessageOriginType|string|null $type = null;

    /**
     * Date the message was sent originally in Unix time
     */
    #[Field('date', required: true)]
    private(set) ?int $date = null;

    /**
     * User that sent the message originally
     */
    #[Field('sender_user', required: true)]
    private(set) ?User $senderUser = null;

}
