<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\ChatBoostSourceSource;

/**
 * The boost was obtained by subscribing to Telegram Premium or by gifting a Telegram Premium subscription to another user.
 *
 * @link https://core.telegram.org/bots/api#chatboostsourcepremium
 */
class ChatBoostSourcePremium extends ChatBoostSource
{
    /**
     * Source of the boost, always "premium"
     */
    #[Field('source', required: true)]
    private(set) ChatBoostSourceSource|string|null $source = null;

    /**
     * User that boosted the chat
     */
    #[Field('user', required: true)]
    private(set) ?User $user = null;

}
