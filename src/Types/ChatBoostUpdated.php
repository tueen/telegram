<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\Chat;
use Tueen\Telegram\Types\ChatBoost;

/**
 * This object represents a boost added to a chat or changed.
 *
 * @link https://core.telegram.org/bots/api#chatboostupdated
 */
class ChatBoostUpdated extends Type
{
    /**
     * Chat which was boosted
     */
    #[Field('chat', required: true)]
    public private(set) Chat $chat;

    /**
     * Information about the chat boost
     */
    #[Field('boost', required: true)]
    public private(set) ChatBoost $boost;

}
