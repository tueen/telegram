<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Describes Telegram Passport data shared with the bot by the user.
 *
 * @link https://core.telegram.org/bots/api#passportdata
 */
class PassportData extends Type
{
    /**
     * Array with information about documents and other Telegram Passport elements that was shared with the bot
     * @var EncryptedPassportElement[]|null
     */
    #[Field('data', required: true)]
    #[ArrayOf(EncryptedPassportElement::class)]
    private(set) array $data;

    /**
     * Encrypted credentials required to decrypt the data
     */
    #[Field('credentials', required: true)]
    private(set) EncryptedCredentials $credentials;

}
