<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

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
    public private(set) string $source;

    /**
     * The section of the user's Telegram Passport which has the issue, one of "driver_license", "identity_card"
     */
    #[Field('type', required: true)]
    public private(set) string $type;

    /**
     * Base64-encoded hash of the file with the reverse side of the document
     */
    #[Field('file_hash', required: true)]
    public private(set) string $fileHash;

    /**
     * Error message
     */
    #[Field('message', required: true)]
    public private(set) string $message;

}
