<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a service message about a video chat scheduled in the chat.
 *
 * @link https://core.telegram.org/bots/api#videochatscheduled
 */
class VideoChatScheduled extends Type
{
    /**
     * Point in time (Unix timestamp) when the video chat is supposed to be started by a chat administrator
     */
    #[Field('start_date', required: true)]
    private(set) int $startDate;

}
