<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Describes the opening hours of a business.
 *
 * @link https://core.telegram.org/bots/api#businessopeninghours
 */
class BusinessOpeningHours extends Type
{
    /**
     * Unique name of the time zone for which the opening hours are defined
     */
    #[Field('time_zone_name', required: true)]
    public private(set) string $timeZoneName;

    /**
     * List of time intervals describing business opening hours
     * @var BusinessOpeningHoursInterval[]|null
     */
    #[Field('opening_hours', required: true)]
    #[ArrayOf(BusinessOpeningHoursInterval::class)]
    public private(set) array $openingHours;

}
