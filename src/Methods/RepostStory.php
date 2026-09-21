<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Story;
use Tueen\Telegram\Enums\StoryActivePeriod;

/**
 * Reposts a story on behalf of a business account from another business account. Both business accounts must be managed by the same bot, and the story on the source account must have been posted (or reposted) by the bot. Requires the can_manage_stories business bot right for both business accounts. Returns Story on success.
 *
 * @link https://core.telegram.org/bots/api#repoststory
 */
#[ApiMethod('repostStory', 'POST')]
#[ReturnType(Story::class, isArray: false)]
class RepostStory extends Method
{
    /**
     * Unique identifier of the business connection
     */
    #[Field('business_connection_id', required: true)]
    public string $businessConnectionId;

    /**
     * Unique identifier of the chat which posted the story that should be reposted
     */
    #[Field('from_chat_id', required: true)]
    public int $fromChatId;

    /**
     * Unique identifier of the story that should be reposted
     */
    #[Field('from_story_id', required: true)]
    public int $fromStoryId;

    /**
     * Period after which the story is moved to the archive, in seconds; must be one of 6 * 3600, 12 * 3600, 86400, or 2 * 86400
     */
    #[Field('active_period', required: true)]
    public StoryActivePeriod|int $activePeriod;

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
        int $fromChatId,
        int $fromStoryId,
        StoryActivePeriod|int $activePeriod,
        ?bool $postToChatPage = null,
        ?bool $protectContent = null,
        mixed ...$extra
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($fromChatId !== null) $this->fromChatId = $fromChatId;
        if ($fromStoryId !== null) $this->fromStoryId = $fromStoryId;
        if ($activePeriod !== null) $this->activePeriod = $activePeriod;
        if ($postToChatPage !== null) $this->postToChatPage = $postToChatPage;
        if ($protectContent !== null) $this->protectContent = $protectContent;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
