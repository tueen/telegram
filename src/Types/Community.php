<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * Represents a community (a group of chats).
 *
 * @link https://core.telegram.org/bots/api#community
 */
class Community extends Type
{
    /**
     * Unique identifier for this community. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this identifier.
     */
    #[Field('id', required: true)]
    private(set) ?int $id = null;

    /**
     * Name of the community
     */
    #[Field('name', required: true)]
    private(set) ?string $name = null;

}
