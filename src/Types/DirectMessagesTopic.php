<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * Describes a topic of a direct messages chat.
 *
 * @link https://core.telegram.org/bots/api#directmessagestopic
 */
class DirectMessagesTopic extends Type
{
    /**
     * Unique identifier of the topic. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier.
     */
    #[Field('topic_id', required: true)]
    private(set) int $topicId;

    /**
     * Optional. Information about the user that created the topic. Currently, it is always present.
     */
    #[Field('user', required: false)]
    private(set) ?User $user = null;

}
