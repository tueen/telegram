<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * This object defines the criteria used to request suitable users. Information about the selected users will be shared with the bot when the corresponding button is pressed. More about requesting users: https://core.telegram.org/bots/features#chat-and-user-selection
 *
 * @link https://core.telegram.org/bots/api#keyboardbuttonrequestusers
 */
class KeyboardButtonRequestUsers extends Type
{
    /**
     * Signed 32-bit identifier of the request that will be received back in the UsersShared object. Must be unique within the message.
     */
    #[Field('request_id', required: true)]
    public private(set) int $requestId;

    /**
     * Optional. Pass True to request bots, pass False to request regular users. If not specified, no additional restrictions are applied.
     */
    #[Field('user_is_bot', required: false)]
    public private(set) ?bool $userIsBot = null;

    /**
     * Optional. Pass True to request premium users, pass False to request non-premium users. If not specified, no additional restrictions are applied.
     */
    #[Field('user_is_premium', required: false)]
    public private(set) ?bool $userIsPremium = null;

    /**
     * Optional. The maximum number of users to be selected; 1-10. Defaults to 1.
     */
    #[Field('max_quantity', required: false)]
    public private(set) ?int $maxQuantity = null;

    /**
     * Optional. Pass True to request the users' first and last names
     */
    #[Field('request_name', required: false)]
    public private(set) ?bool $requestName = null;

    /**
     * Optional. Pass True to request the users' usernames
     */
    #[Field('request_username', required: false)]
    public private(set) ?bool $requestUsername = null;

    /**
     * Optional. Pass True to request the users' photos
     */
    #[Field('request_photo', required: false)]
    public private(set) ?bool $requestPhoto = null;

}
