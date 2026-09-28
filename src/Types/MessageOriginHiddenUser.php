<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
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
    private(set) MessageOriginType|string|null $type = null;

    /**
     * Date the message was sent originally in Unix time
     */
    #[Field('date', required: true)]
    private(set) ?int $date = null;

    /**
     * Name of the user that sent the message originally
     */
    #[Field('sender_user_name', required: true)]
    private(set) ?string $senderUserName = null;

}
