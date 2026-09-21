<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\ParseMode;

/**
 * This object contains information about one answer option in a poll to be sent.
 *
 * @link https://core.telegram.org/bots/api#inputpolloption
 */
class InputPollOption extends Type
{
    /**
     * Option text, 1-100 characters
     */
    #[Field('text', required: true)]
    public private(set) string $text;

    /**
     * Optional. Mode for parsing entities in the text. See formatting options for more details. Currently, only custom emoji entities are allowed.
     */
    #[Field('text_parse_mode', required: false)]
    public private(set) ParseMode|string|null $textParseMode = null;

    /**
     * Optional. A JSON-serialized list of special entities that appear in the poll option text. It can be specified instead of text_parse_mode.
     * @var MessageEntity[]|null
     */
    #[Field('text_entities', required: false)]
    #[ArrayOf(MessageEntity::class)]
    public private(set) ?array $textEntities = null;

    /**
     * Optional. Media added to the poll option
     */
    #[Field('media', required: false)]
    public private(set) ?InputPollOptionMedia $media = null;

}
