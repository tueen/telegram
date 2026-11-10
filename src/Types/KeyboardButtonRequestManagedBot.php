<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object defines the parameters for the creation of a managed bot. Information about the created bot will be shared with the bot using the update managed_bot and a Message with the field managed_bot_created.
 *
 * @link https://core.telegram.org/bots/api#keyboardbuttonrequestmanagedbot
 */
class KeyboardButtonRequestManagedBot extends Type
{
    /**
     * Signed 32-bit identifier of the request. Must be unique within the message.
     */
    #[Field('request_id', required: true)]
    private(set) ?int $requestId = null;

    /**
     * Optional. Suggested name for the bot
     */
    #[Field('suggested_name', required: false)]
    private(set) ?string $suggestedName = null;

    /**
     * Optional. Suggested username for the bot. The username must end with "bot". If the user creating the bot has a Telegram Premium subscription, then the username can also end with "agent", "ai", "app", "game", or "gay"; in this case, the username will be assigned to the bot as a secondary username, and a regular username ending with "bot" will be assigned to the bot automatically.
     */
    #[Field('suggested_username', required: false)]
    private(set) ?string $suggestedUsername = null;

}
