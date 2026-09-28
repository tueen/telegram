<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object contains information about the creation, token update, or owner update of a bot that is managed by the current bot.
 *
 * @link https://core.telegram.org/bots/api#managedbotupdated
 */
class ManagedBotUpdated extends Type
{
    /**
     * User that created the bot
     */
    #[Field('user', required: true)]
    private(set) User $user;

    /**
     * Information about the bot. Token of the bot can be fetched using the method getManagedBotToken.
     */
    #[Field('bot', required: true)]
    private(set) User $bot;

}
