<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\RichText;

/**
 * Formatted date and time.
 *
 * @link https://core.telegram.org/bots/api#richtextdatetime
 */
class RichTextDateTime extends RichText
{
    /**
     * Type of the rich text, always "date_time"
     */
    #[Field('type', required: true)]
    public private(set) string $type;

    /**
     * The text
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

    /**
     * The Unix time associated with the entity
     */
    #[Field('unix_time', required: true)]
    public private(set) int $unixTime;

    /**
     * The string that defines the formatting of the date and time. See date-time entity formatting for more details.
     */
    #[Field('date_time_format', required: true)]
    public private(set) string $dateTimeFormat;

}
