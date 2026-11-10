<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * The theme based on a unique gift owned by the user or the chat.
 *
 * @link https://core.telegram.org/bots/api#chatsharethemeuniquegift
 */
class ChatShareThemeUniqueGift extends ChatShareTheme
{
    /**
     * Type of the theme, always "unique_gift"
     */
    #[Field('type', required: true)]
    private(set) ?string $type = null;

    /**
     * The gift
     */
    #[Field('gift', required: true)]
    private(set) ?UniqueGift $gift = null;

}
