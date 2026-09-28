<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichTextType;

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
    private(set) RichTextType|string|null $type = null;

    /**
     * The text
     */
    #[Field('text', required: true)]
    private(set) ?RichText $text = null;

    /**
     * The bot command
     */
    #[Field('bot_command', required: true)]
    private(set) ?string $botCommand = null;

}
