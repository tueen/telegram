<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

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
    private(set) int $duration;

}
