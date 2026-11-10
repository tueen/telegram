<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object describes a backdrop, which can be used as the background of a theme of the screen for sharing a link to a user or a chat.
 *
 * @link https://core.telegram.org/bots/api#chatsharethemebackdrop
 */
class ChatShareThemeBackdrop extends Type
{
    /**
     * Unique identifier of the backdrop
     */
    #[Field('id', required: true)]
    private(set) ?int $id = null;

    /**
     * Name of the backdrop
     */
    #[Field('name', required: true)]
    private(set) ?string $name = null;

    /**
     * Colors of the backdrop
     */
    #[Field('colors', required: true)]
    private(set) ?UniqueGiftBackdropColors $colors = null;

}
