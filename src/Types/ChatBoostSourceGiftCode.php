<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\ChatBoostSourceSource;

/**
 * The boost was obtained by the creation of Telegram Premium gift codes to boost a chat. Each such code boosts the chat 4 times for the duration of the corresponding Telegram Premium subscription.
 *
 * @link https://core.telegram.org/bots/api#chatboostsourcegiftcode
 */
class ChatBoostSourceGiftCode extends ChatBoostSource
{
    /**
     * Source of the boost, always "gift_code"
     */
    #[Field('source', required: true)]
    private(set) ChatBoostSourceSource|string|null $source = null;

    /**
     * User for which the gift code was created
     */
    #[Field('user', required: true)]
    private(set) ?User $user = null;

}
