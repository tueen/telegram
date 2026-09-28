<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

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
    private(set) ?Chat $chat = null;

    /**
     * Unique identifier for the story in the chat
     */
    #[Field('id', required: true)]
    private(set) ?int $id = null;

}
