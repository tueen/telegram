<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * Describes an interval of time during which a business is open.
 *
 * @link https://core.telegram.org/bots/api#businessopeninghoursinterval
 */
class BusinessOpeningHoursInterval extends Type
{
    /**
     * The minute's sequence number in a week, starting on Monday, marking the start of the time interval during which the business is open; 0 - 7 * 24 * 60
     */
    #[Field('opening_minute', required: true)]
    private(set) int $openingMinute;

    /**
     * The minute's sequence number in a week, starting on Monday, marking the end of the time interval during which the business is open; 0 - 8 * 24 * 60
     */
    #[Field('closing_minute', required: true)]
    private(set) int $closingMinute;

}
