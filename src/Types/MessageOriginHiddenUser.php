<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\MessageOriginType;

/**
 * The message was originally sent by an unknown user.
 *
 * @link https://core.telegram.org/bots/api#messageoriginhiddenuser
 */
class MessageOriginHiddenUser extends MessageOrigin
{
    /**
     * Type of the message origin, always "hidden_user"
     */
    #[Field('type', required: true)]
    public private(set) MessageOriginType|string $type;

    /**
     * Date the message was sent originally in Unix time
     */
    #[Field('date', required: true)]
    public private(set) int $date;

    /**
     * Name of the user that sent the message originally
     */
    #[Field('sender_user_name', required: true)]
    public private(set) string $senderUserName;

}
