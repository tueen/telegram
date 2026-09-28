<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Types\InputProfilePhoto;

/**
 * Changes the profile photo of a managed business account. Requires the can_edit_profile_photo business bot right. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setbusinessaccountprofilephoto
 */
#[ApiMethod('setBusinessAccountProfilePhoto', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SetBusinessAccountProfilePhoto extends Method
{
    /**
     * Unique identifier of the business connection
     */
    #[Field('business_connection_id', required: true)]
    public string $businessConnectionId;

    /**
     * The new profile photo to set
     */
    #[Field('photo', required: true)]
    public InputProfilePhoto $photo;

    /**
     * Pass True to set the public photo, which will be visible even if the main photo is hidden by the business account's privacy settings. An account can have only one public photo.
     */
    #[Field('is_public', required: false)]
    public ?bool $isPublic = null;

    public function __construct(
        string $businessConnectionId,
        InputProfilePhoto $photo,
        ?bool $isPublic = null,
        mixed ...$extra
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($photo !== null) $this->photo = $photo;
        if ($isPublic !== null) $this->isPublic = $isPublic;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
