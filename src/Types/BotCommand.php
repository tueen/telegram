<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * This object represents a bot command.
 *
 * @link https://core.telegram.org/bots/api#botcommand
 */
class BotCommand extends Type
{
    /**
     * Text of the command; 1-32 characters. Can contain only lowercase English letters, digits and underscores.
     */
    #[Field('command', required: true)]
    public private(set) string $command;

    /**
     * Description of the command; 1-256 characters
     */
    #[Field('description', required: true)]
    public private(set) string $description;

    /**
     * Optional. True, if the command sends an ephemeral message, which can be seen only by the sender of the message and the bot
     */
    #[Field('is_ephemeral', required: false)]
    public private(set) ?bool $isEphemeral = null;

}
