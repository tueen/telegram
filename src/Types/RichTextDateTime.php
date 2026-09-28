<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichTextType;

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
    private(set) RichTextType|string|null $type = null;

    /**
     * The text
     */
    #[Field('text', required: true)]
    private(set) ?RichText $text = null;

    /**
     * The Unix time associated with the entity
     */
    #[Field('unix_time', required: true)]
    private(set) ?int $unixTime = null;

    /**
     * The string that defines the formatting of the date and time. See date-time entity formatting for more details.
     */
    #[Field('date_time_format', required: true)]
    private(set) ?string $dateTimeFormat = null;

}
