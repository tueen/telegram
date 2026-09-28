<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\PassportSource;
use Tueen\Telegram\Enums\PassportType;

/**
 * Represents an issue with the front side of a document. The error is considered resolved when the file with the front side of the document changes.
 *
 * @link https://core.telegram.org/bots/api#passportelementerrorfrontside
 */
class PassportElementErrorFrontSide extends PassportElementError
{
    /**
     * Error source, must be front_side
     */
    #[Field('source', required: true)]
    private(set) PassportSource|string $source;

    /**
     * The section of the user's Telegram Passport which has the issue, one of "passport", "driver_license", "identity_card", "internal_passport"
     */
    #[Field('type', required: true)]
    private(set) PassportType|string $type;

    /**
     * Base64-encoded hash of the file with the front side of the document
     */
    #[Field('file_hash', required: true)]
    private(set) string $fileHash;

    /**
     * Error message
     */
    #[Field('message', required: true)]
    private(set) string $message;

}
