<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\PassportSource;
use Tueen\Telegram\Enums\PassportType;

/**
 * Represents an issue with the reverse side of a document. The error is considered resolved when the file with reverse side of the document changes.
 *
 * @link https://core.telegram.org/bots/api#passportelementerrorreverseside
 */
class PassportElementErrorReverseSide extends PassportElementError
{
    /**
     * Error source, must be reverse_side
     */
    #[Field('source', required: true)]
    private(set) PassportSource|string|null $source = null;

    /**
     * The section of the user's Telegram Passport which has the issue, one of "driver_license", "identity_card"
     */
    #[Field('type', required: true)]
    private(set) PassportType|string|null $type = null;

    /**
     * Base64-encoded hash of the file with the reverse side of the document
     */
    #[Field('file_hash', required: true)]
    private(set) ?string $fileHash = null;

    /**
     * Error message
     */
    #[Field('message', required: true)]
    private(set) ?string $message = null;

}
