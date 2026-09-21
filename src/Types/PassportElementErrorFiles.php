<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * Represents an issue with a list of scans. The error is considered resolved when the list of files containing the scans changes.
 *
 * @link https://core.telegram.org/bots/api#passportelementerrorfiles
 */
class PassportElementErrorFiles extends PassportElementError
{
    /**
     * Error source, must be files
     */
    #[Field('source', required: true)]
    public private(set) string $source;

    /**
     * The section of the user's Telegram Passport which has the issue, one of "utility_bill", "bank_statement", "rental_agreement", "passport_registration", "temporary_registration"
     */
    #[Field('type', required: true)]
    public private(set) string $type;

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
