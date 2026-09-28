<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\PassportSource;
use Tueen\Telegram\Enums\PassportType;

/**
 * Represents an issue with a document scan. The error is considered resolved when the file with the document scan changes.
 *
 * @link https://core.telegram.org/bots/api#passportelementerrorfile
 */
class PassportElementErrorFile extends PassportElementError
{
    /**
     * Error source, must be file
     */
    #[Field('source', required: true)]
    private(set) PassportSource|string $source;

    /**
     * The section of the user's Telegram Passport which has the issue, one of "utility_bill", "bank_statement", "rental_agreement", "passport_registration", "temporary_registration"
     */
    #[Field('type', required: true)]
    private(set) PassportType|string $type;

    /**
     * Base64-encoded file hash
     */
    #[Field('file_hash', required: true)]
    private(set) string $fileHash;

    /**
     * Error message
     */
    #[Field('message', required: true)]
    private(set) string $message;

}
