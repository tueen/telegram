<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object contains a list of backdrops, which can be used as the background of a theme of the screen for sharing a link to a user or a chat.
 *
 * @link https://core.telegram.org/bots/api#chatsharethemebackdrops
 */
class ChatShareThemeBackdrops extends Type
{
    /**
     * The list of backdrops
     * @var ChatShareThemeBackdrop[]|null
     */
    #[Field('backdrops', required: true)]
    #[ArrayOf(ChatShareThemeBackdrop::class)]
    private(set) ?array $backdrops = null;

}
