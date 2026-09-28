<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\BusinessConnectionRevokedException;
use Tueen\Telegram\Exceptions\NotEnoughRightsException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Deletes a story previously posted by the bot on behalf of a managed business account. Requires the can_manage_stories business bot right. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#deletestory
 *
 * @throws NotEnoughRightsException
 * @throws BusinessConnectionRevokedException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('deleteStory', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::NotEnoughRights, TelegramErrorCode::BusinessConnectionRevoked, TelegramErrorCode::FloodWait])]
class DeleteStory extends Method
{
    /**
     * Unique identifier of the business connection
     */
    #[Field('business_connection_id', required: true)]
    public ?string $businessConnectionId = null;

    /**
     * Unique identifier of the story to delete
     */
    #[Field('story_id', required: true)]
    public ?int $storyId = null;

    public function __construct(
        ?string $businessConnectionId = null,
        ?int $storyId = null,
        mixed ...$extra
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($storyId !== null) $this->storyId = $storyId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
