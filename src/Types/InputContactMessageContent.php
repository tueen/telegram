<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * Represents the content of a contact message to be sent as the result of an inline query.
 *
 * @link https://core.telegram.org/bots/api#inputcontactmessagecontent
 */
class InputContactMessageContent extends InputMessageContent
{
    /**
     * Contact's phone number
     */
    #[Field('phone_number', required: true)]
    public private(set) string $phoneNumber;

    /**
     * Contact's first name
     */
    #[Field('first_name', required: true)]
    public private(set) string $firstName;

    /**
     * Optional. Contact's last name
     */
    #[Field('last_name', required: false)]
    public private(set) ?string $lastName = null;

    /**
     * Optional. Additional data about the contact in the form of a vCard, 0-2048 bytes
     */
    #[Field('vcard', required: false)]
    public private(set) ?string $vcard = null;

}
