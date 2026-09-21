<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * This object represents a service message about a video chat ended in the chat.
 *
 * @link https://core.telegram.org/bots/api#videochatended
 */
class VideoChatEnded extends Type
{
    /**
     * Video chat duration in seconds
     */
    #[Field('duration', required: true)]
    public private(set) int $duration;

}
