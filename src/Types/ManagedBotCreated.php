<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object contains information about the bot that was created to be managed by the current bot.
 *
 * @link https://core.telegram.org/bots/api#managedbotcreated
 */
class ManagedBotCreated extends Type
{
    /**
     * Information about the bot. The bot's token can be fetched using the method getManagedBotToken.
     */
    #[Field('bot', required: true)]
    public private(set) User $bot;

}
