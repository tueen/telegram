<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\PassportFile;

/**
 * Describes documents or other Telegram Passport elements shared with the bot by the user.
 *
 * @link https://core.telegram.org/bots/api#encryptedpassportelement
 */
class EncryptedPassportElement extends Type
{
    /**
     * Element type. One of "personal_details", "passport", "driver_license", "identity_card", "internal_passport", "address", "utility_bill", "bank_statement", "rental_agreement", "passport_registration", "temporary_registration", "phone_number", "email".
     */
    #[Field('type', required: true)]
    public private(set) string $type;

    /**
     * Optional. Base64-encoded encrypted Telegram Passport element data provided by the user; available only for "personal_details", "passport", "driver_license", "identity_card", "internal_passport" and "address" types. Can be decrypted and verified using the accompanying EncryptedCredentials.
     */
    #[Field('data', required: false)]
    public private(set) ?string $data = null;

    /**
     * Optional. User's verified phone number; available only for "phone_number" type
     */
    #[Field('phone_number', required: false)]
    public private(set) ?string $phoneNumber = null;

    /**
     * Optional. User's verified email address; available only for "email" type
     */
    #[Field('email', required: false)]
    public private(set) ?string $email = null;

    /**
     * Optional. Array of encrypted files with documents provided by the user; available only for "utility_bill", "bank_statement", "rental_agreement", "passport_registration" and "temporary_registration" types. Files can be decrypted and verified using the accompanying EncryptedCredentials.
     * @var PassportFile[]|null
     */
    #[Field('files', required: false)]
    #[ArrayOf(PassportFile::class)]
    public private(set) ?array $files = null;

    /**
     * Optional. Encrypted file with the front side of the document, provided by the user; available only for "passport", "driver_license", "identity_card" and "internal_passport". The file can be decrypted and verified using the accompanying EncryptedCredentials.
     */
    #[Field('front_side', required: false)]
    public private(set) ?PassportFile $frontSide = null;

    /**
     * Optional. Encrypted file with the reverse side of the document, provided by the user; available only for "driver_license" and "identity_card". The file can be decrypted and verified using the accompanying EncryptedCredentials.
     */
    #[Field('reverse_side', required: false)]
    public private(set) ?PassportFile $reverseSide = null;

    /**
     * Optional. Encrypted file with the selfie of the user holding a document, provided by the user; available if requested for "passport", "driver_license", "identity_card" and "internal_passport". The file can be decrypted and verified using the accompanying EncryptedCredentials.
     */
    #[Field('selfie', required: false)]
    public private(set) ?PassportFile $selfie = null;

    /**
     * Optional. Array of encrypted files with translated versions of documents provided by the user; available if requested for "passport", "driver_license", "identity_card", "internal_passport", "utility_bill", "bank_statement", "rental_agreement", "passport_registration" and "temporary_registration" types. Files can be decrypted and verified using the accompanying EncryptedCredentials.
     * @var PassportFile[]|null
     */
    #[Field('translation', required: false)]
    #[ArrayOf(PassportFile::class)]
    public private(set) ?array $translation = null;

    /**
     * Base64-encoded element hash for using in PassportElementErrorUnspecified
     */
    #[Field('hash', required: true)]
    public private(set) string $hash;

}
