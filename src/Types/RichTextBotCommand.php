<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\RichTextType;
use Tueen\Telegram\Types\RichText;

/**
 * A bot command.
 *
 * @link https://core.telegram.org/bots/api#richtextbotcommand
 */
class RichTextBotCommand extends RichText
{
    /**
     * Type of the rich text, always "bot_command"
     */
    #[Field('type', required: true)]
    public private(set) RichTextType|string $type;

    /**
     * The text
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

    /**
     * The bot command
     */
    #[Field('bot_command', required: true)]
    public private(set) string $botCommand;

}
