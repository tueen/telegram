<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a boost removed from a chat.
 *
 * @link https://core.telegram.org/bots/api#chatboostremoved
 */
class ChatBoostRemoved extends Type
{
    /**
     * Chat which was boosted
     */
    #[Field('chat', required: true)]
    public private(set) Chat $chat;

    /**
     * Unique identifier of the boost
     */
    #[Field('boost_id', required: true)]
    public private(set) string $boostId;

    /**
     * Point in time (Unix timestamp) when the boost was removed
     */
    #[Field('remove_date', required: true)]
    public private(set) int $removeDate;

    /**
     * Source of the removed boost
     */
    #[Field('source', required: true)]
    public private(set) ChatBoostSource $source;

}
