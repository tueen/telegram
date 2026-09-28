<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * 
 *
 * @link https://core.telegram.org/bots/api#ephemeralmessageparameters
 */
class EphemeralMessageParameters extends Type
{
    /**
     * Identifier of the user who will receive the message. It is not guaranteed that the user will receive the message, especially if they are offline. See here for more details.
     */
    #[Field('receiver_user_id', required: true)]
    private(set) int $receiverUserId;

    /**
     * Optional. Identifier of the callback query which triggered the message, if any
     */
    #[Field('callback_query_id', required: false)]
    private(set) ?string $callbackQueryId = null;

    /**
     * Optional. Pass True if the ephemeral message must be shown in place of the original message. Must be False for callback queries from ephemeral messages, which must be edited using regular editEphemeralMessage... methods.
     */
    #[Field('replace_callback_query_message', required: false)]
    private(set) ?bool $replaceCallbackQueryMessage = null;

}
