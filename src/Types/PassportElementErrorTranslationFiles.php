<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\PassportSource;
use Tueen\Telegram\Enums\PassportType;

/**
 * Represents an issue with the translated version of a document. The error is considered resolved when a file with the document translation change.
 *
 * @link https://core.telegram.org/bots/api#passportelementerrortranslationfiles
 */
class PassportElementErrorTranslationFiles extends PassportElementError
{
    /**
     * Error source, must be translation_files
     */
    #[Field('source', required: true)]
    public private(set) PassportSource|string $source;

    /**
     * Type of element of the user's Telegram Passport which has the issue, one of "passport", "driver_license", "identity_card", "internal_passport", "utility_bill", "bank_statement", "rental_agreement", "passport_registration", "temporary_registration"
     */
    #[Field('type', required: true)]
    public private(set) PassportType|string $type;

    /**
     * List of base64-encoded file hashes
     * @var String[]|null
     */
    #[Field('file_hashes', required: true)]
    public private(set) array $fileHashes;

    /**
     * Error message
     */
    #[Field('message', required: true)]
    public private(set) string $message;

}
