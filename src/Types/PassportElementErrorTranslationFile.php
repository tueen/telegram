<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\PassportSource;
use Tueen\Telegram\Enums\PassportType;

/**
 * Represents an issue with one of the files that constitute the translation of a document. The error is considered resolved when the file changes.
 *
 * @link https://core.telegram.org/bots/api#passportelementerrortranslationfile
 */
class PassportElementErrorTranslationFile extends PassportElementError
{
    /**
     * Error source, must be translation_file
     */
    #[Field('source', required: true)]
    public private(set) PassportSource|string $source;

    /**
     * Type of element of the user's Telegram Passport which has the issue, one of "passport", "driver_license", "identity_card", "internal_passport", "utility_bill", "bank_statement", "rental_agreement", "passport_registration", "temporary_registration"
     */
    #[Field('type', required: true)]
    public private(set) PassportType|string $type;

    /**
     * Base64-encoded file hash
     */
    #[Field('file_hash', required: true)]
    public private(set) string $fileHash;

    /**
     * Error message
     */
    #[Field('message', required: true)]
    public private(set) string $message;

}
