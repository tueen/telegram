<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\BackgroundType;

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
    public private(set) BackgroundType $type;

}
