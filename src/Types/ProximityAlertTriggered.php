<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents the content of a service message, sent whenever a user in the chat triggers a proximity alert set by another user.
 *
 * @link https://core.telegram.org/bots/api#proximityalerttriggered
 */
class ProximityAlertTriggered extends Type
{
    /**
     * User that triggered the alert
     */
    #[Field('traveler', required: true)]
    private(set) ?User $traveler = null;

    /**
     * User that set the alert
     */
    #[Field('watcher', required: true)]
    private(set) ?User $watcher = null;

    /**
     * The distance between the users
     */
    #[Field('distance', required: true)]
    private(set) ?int $distance = null;

}
