<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\Chat;

/**
 * This object describes an update about a user stopping message generation.
 *
 * @link https://core.telegram.org/bots/api#messagegenerationstopped
 */
class MessageGenerationStopped extends Type
{
    /**
     * Chat in which the message is generated
     */
    #[Field('chat', required: true)]
    public private(set) Chat $chat;

    /**
     * Optional. Unique identifier of the message thread in which the message is generated
     */
    #[Field('message_thread_id', required: false)]
    public private(set) ?int $messageThreadId = null;

    /**
     * Unique identifier of the message draft which was stopped
     */
    #[Field('draft_id', required: true)]
    public private(set) int $draftId;

}
