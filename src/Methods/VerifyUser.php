<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\NotEnoughRightsException;
use Tueen\Telegram\Exceptions\UserCantBeVerifiedException;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Verifies a user on behalf of the organization which is represented by the bot. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#verifyuser
 *
 * @throws UserCantBeVerifiedException
 * @throws NotEnoughRightsException
 * @throws ApiException
 */
#[ApiMethod('verifyUser', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::UserCantBeVerified, TelegramErrorCode::NotEnoughRights])]
class VerifyUser extends Method
{
    /**
     * Unique identifier of the target user
     */
    #[Field('user_id', required: true)]
    public ?int $userId = null;

    /**
     * Custom description for the verification; 0-70 characters. Must be empty if the organization isn't allowed to provide a custom verification description.
     */
    #[Field('custom_description', required: false)]
    public ?string $customDescription = null;

    /**
     * Mode for parsing entities in the custom description. See formatting options for more details. Only "text_link" entities are supported.
     */
    #[Field('custom_description_parse_mode', required: false)]
    public ?string $customDescriptionParseMode = null;

    /**
     * A JSON-serialized list of special entities that appear in the custom description, which can be specified instead of custom_description_parse_mode. Only "text_link" entities are supported.
     */
    #[Field('custom_description_entities', required: false)]
    public ?array $customDescriptionEntities = null;

    public function __construct(
        ?int $userId = null,
        ?string $customDescription = null,
        ?string $customDescriptionParseMode = null,
        ?array $customDescriptionEntities = null,
        mixed ...$extra
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($customDescription !== null) $this->customDescription = $customDescription;
        if ($customDescriptionParseMode !== null) $this->customDescriptionParseMode = $customDescriptionParseMode;
        if ($customDescriptionEntities !== null) $this->customDescriptionEntities = $customDescriptionEntities;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
