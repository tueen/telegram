<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\ParseMode;

/**
 * Describes a task to add to a checklist.
 *
 * @link https://core.telegram.org/bots/api#inputchecklisttask
 */
class InputChecklistTask extends Type
{
    /**
     * Unique identifier of the task; must be positive and unique among all task identifiers currently present in the checklist
     */
    #[Field('id', required: true)]
    public private(set) int $id;

    /**
     * Text of the task; 1-100 characters after entities parsing
     */
    #[Field('text', required: true)]
    public private(set) string $text;

    /**
     * Optional. Mode for parsing entities in the text. See formatting options for more details.
     */
    #[Field('parse_mode', required: false)]
    public private(set) ParseMode|string|null $parseMode = null;

    /**
     * Optional. List of special entities that appear in the text, which can be specified instead of parse_mode. Currently, only bold, italic, underline, strikethrough, spoiler, custom_emoji, and date_time entities are allowed.
     * @var MessageEntity[]|null
     */
    #[Field('text_entities', required: false)]
    #[ArrayOf(MessageEntity::class)]
    public private(set) ?array $textEntities = null;

}
