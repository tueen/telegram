<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Story;
use Tueen\Telegram\Types\InputStoryContent;

/**
 * Posts a story on behalf of a managed business account. Requires the can_manage_stories business bot right. Returns Story on success.
 *
 * @link https://core.telegram.org/bots/api#poststory
 */
#[ApiMethod('postStory', 'POST')]
#[ReturnType(Story::class, isArray: false)]
class PostStory extends Method
{
    /**
     * Unique identifier of the business connection
     */
    #[Field('business_connection_id', required: true)]
    public string $businessConnectionId;

    /**
     * Content of the story
     */
    #[Field('content', required: true)]
    public InputStoryContent $content;

    /**
     * Period after which the story is moved to the archive, in seconds; must be one of 6 * 3600, 12 * 3600, 86400, or 2 * 86400
     */
    #[Field('active_period', required: true)]
    public int $activePeriod;

    /**
     * Caption of the story, 0-2048 characters after entities parsing
     */
    #[Field('caption', required: false)]
    public ?string $caption = null;

    /**
     * Mode for parsing entities in the story caption. See formatting options for more details.
     */
    #[Field('parse_mode', required: false)]
    public ?string $parseMode = null;

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

    /**
     * Pass True to keep the story accessible after it expires
     */
    #[Field('post_to_chat_page', required: false)]
    public ?bool $postToChatPage = null;

    /**
     * Pass True if the content of the story must be protected from forwarding and screenshotting
     */
    #[Field('protect_content', required: false)]
    public ?bool $protectContent = null;

    public function __construct(
        string $businessConnectionId,
        InputStoryContent $content,
        int $activePeriod,
        ?string $caption = null,
        ?string $parseMode = null,
        ?array $captionEntities = null,
        ?array $areas = null,
        ?bool $postToChatPage = null,
        ?bool $protectContent = null
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($content !== null) $this->content = $content;
        if ($activePeriod !== null) $this->activePeriod = $activePeriod;
        if ($caption !== null) $this->caption = $caption;
        if ($parseMode !== null) $this->parseMode = $parseMode;
        if ($captionEntities !== null) $this->captionEntities = $captionEntities;
        if ($areas !== null) $this->areas = $areas;
        if ($postToChatPage !== null) $this->postToChatPage = $postToChatPage;
        if ($protectContent !== null) $this->protectContent = $protectContent;
    }

    public static function make(
        string $businessConnectionId,
        InputStoryContent $content,
        int $activePeriod,
        ?string $caption = null,
        ?string $parseMode = null,
        ?array $captionEntities = null,
        ?array $areas = null,
        ?bool $postToChatPage = null,
        ?bool $protectContent = null
    ): static
    {
        return new static($businessConnectionId, $content, $activePeriod, $caption, $parseMode, $captionEntities, $areas, $postToChatPage, $protectContent);
    }
}
