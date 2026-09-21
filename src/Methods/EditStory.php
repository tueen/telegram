<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Story;
use Tueen\Telegram\Types\InputStoryContent;
use Tueen\Telegram\Enums\ParseMode;

/**
 * Edits a story previously posted by the bot on behalf of a managed business account. Requires the can_manage_stories business bot right. Returns Story on success.
 *
 * @link https://core.telegram.org/bots/api#editstory
 */
#[ApiMethod('editStory', 'POST')]
#[ReturnType(Story::class, isArray: false)]
class EditStory extends Method
{
    /**
     * Unique identifier of the business connection
     */
    #[Field('business_connection_id', required: true)]
    public string $businessConnectionId;

    /**
     * Unique identifier of the story to edit
     */
    #[Field('story_id', required: true)]
    public int $storyId;

    /**
     * Content of the story
     */
    #[Field('content', required: true)]
    public InputStoryContent $content;

    /**
     * Caption of the story, 0-2048 characters after entities parsing
     */
    #[Field('caption', required: false)]
    public ?string $caption = null;

    /**
     * Mode for parsing entities in the story caption. See formatting options for more details.
     */
    #[Field('parse_mode', required: false)]
    public ParseMode|string|null $parseMode = null;

    /**
     * A JSON-serialized list of special entities that appear in the caption, which can be specified instead of parse_mode
     */
    #[Field('caption_entities', required: false)]
    public ?array $captionEntities = null;

    /**
     * A JSON-serialized list of clickable areas to be shown on the story
     */
    #[Field('areas', required: false)]
    public ?array $areas = null;

    public function __construct(
        string $businessConnectionId,
        int $storyId,
        InputStoryContent $content,
        ?string $caption = null,
        ParseMode|string|null $parseMode = null,
        ?array $captionEntities = null,
        ?array $areas = null
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($storyId !== null) $this->storyId = $storyId;
        if ($content !== null) $this->content = $content;
        if ($caption !== null) $this->caption = $caption;
        if ($parseMode !== null) $this->parseMode = $parseMode;
        if ($captionEntities !== null) $this->captionEntities = $captionEntities;
        if ($areas !== null) $this->areas = $areas;
    }

    public static function make(
        string $businessConnectionId,
        int $storyId,
        InputStoryContent $content,
        ?string $caption = null,
        ParseMode|string|null $parseMode = null,
        ?array $captionEntities = null,
        ?array $areas = null
    ): static
    {
        return new static($businessConnectionId, $storyId, $content, $caption, $parseMode, $captionEntities, $areas);
    }
}
