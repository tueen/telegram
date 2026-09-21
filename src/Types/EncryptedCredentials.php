<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * Describes data required for decrypting and authenticating EncryptedPassportElement. See the Telegram Passport Documentation for a complete description of the data decryption and authentication processes.
 *
 * @link https://core.telegram.org/bots/api#encryptedcredentials
 */
class EncryptedCredentials extends Type
{
    /**
     * Base64-encoded encrypted JSON-serialized data with unique user's payload, data hashes and secrets required for EncryptedPassportElement decryption and authentication
     */
    #[Field('data', required: true)]
    public private(set) string $data;

    /**
     * Base64-encoded data hash for data authentication
     */
    #[Field('hash', required: true)]
    public private(set) string $hash;

    /**
     * Base64-encoded secret, encrypted with the bot's public RSA key, required for data decryption
     */
    #[Field('secret', required: true)]
    public private(set) string $secret;

}
