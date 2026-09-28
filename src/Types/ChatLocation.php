<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

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
    private(set) ?Location $location = null;

    /**
     * Location address; 1-64 characters, as defined by the chat owner
     */
    #[Field('address', required: true)]
    private(set) ?string $address = null;

}
