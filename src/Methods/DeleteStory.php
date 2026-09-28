<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Deletes a story previously posted by the bot on behalf of a managed business account. Requires the can_manage_stories business bot right. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#deletestory
 */
#[ApiMethod('deleteStory', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class DeleteStory extends Method
{
    /**
     * Unique identifier of the business connection
     */
    #[Field('business_connection_id', required: true)]
    public string $businessConnectionId;

    /**
     * Unique identifier of the story to delete
     */
    #[Field('story_id', required: true)]
    public int $storyId;

    public function __construct(
        string $businessConnectionId,
        int $storyId,
        mixed ...$extra
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($storyId !== null) $this->storyId = $storyId;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
