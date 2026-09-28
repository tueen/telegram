<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Removes the current profile photo of a managed business account. Requires the can_edit_profile_photo business bot right. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#removebusinessaccountprofilephoto
 */
#[ApiMethod('removeBusinessAccountProfilePhoto', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class RemoveBusinessAccountProfilePhoto extends Method
{
    /**
     * Unique identifier of the business connection
     */
    #[Field('business_connection_id', required: true)]
    public string $businessConnectionId;

    /**
     * Pass True to remove the public photo, which is visible even if the main photo is hidden by the business account's privacy settings. After the main photo is removed, the previous profile photo (if present) becomes the main photo.
     */
    #[Field('is_public', required: false)]
    public ?bool $isPublic = null;

    public function __construct(
        string $businessConnectionId,
        ?bool $isPublic = null,
        mixed ...$extra
    )
    {
        $this->businessConnectionId = $businessConnectionId;
        if ($isPublic !== null) $this->isPublic = $isPublic;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
