<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a story.
 *
 * @link https://core.telegram.org/bots/api#story
 */
class Story extends Type
{
    /**
     * Chat that posted the story
     */
    #[Field('chat', required: true)]
    public private(set) Chat $chat;

    /**
     * Unique identifier for the story in the chat
     */
    #[Field('id', required: true)]
    public private(set) int $id;

}
