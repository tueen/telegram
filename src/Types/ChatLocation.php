<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\Location;

/**
 * Represents a location to which a chat is connected.
 *
 * @link https://core.telegram.org/bots/api#chatlocation
 */
class ChatLocation extends Type
{
    /**
     * The location to which the supergroup is connected. Can't be a live location.
     */
    #[Field('location', required: true)]
    public private(set) Location $location;

    /**
     * Location address; 1-64 characters, as defined by the chat owner
     */
    #[Field('address', required: true)]
    public private(set) string $address;

}
