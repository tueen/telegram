<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a phone contact.
 *
 * @link https://core.telegram.org/bots/api#contact
 */
class Contact extends Type
{
    /**
     * Contact's phone number
     */
    #[Field('phone_number', required: true)]
    private(set) string $phoneNumber;

    /**
     * Contact's first name
     */
    #[Field('first_name', required: true)]
    private(set) string $firstName;

    /**
     * Optional. Contact's last name
     */
    #[Field('last_name', required: false)]
    private(set) ?string $lastName = null;

    /**
     * Optional. Contact's user identifier in Telegram. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier.
     */
    #[Field('user_id', required: false)]
    private(set) ?int $userId = null;

    /**
     * Optional. Additional data about the contact in the form of a vCard
     */
    #[Field('vcard', required: false)]
    private(set) ?string $vcard = null;

}
