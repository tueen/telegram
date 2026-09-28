<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a chat background.
 *
 * @link https://core.telegram.org/bots/api#chatbackground
 */
class ChatBackground extends Type
{
    /**
     * Type of the background
     */
    #[Field('type', required: true)]
    private(set) ?BackgroundType $type = null;

}
