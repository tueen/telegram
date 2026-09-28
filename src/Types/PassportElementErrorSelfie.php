<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\PassportSource;
use Tueen\Telegram\Enums\PassportType;

/**
 * Represents an issue with the selfie with a document. The error is considered resolved when the file with the selfie changes.
 *
 * @link https://core.telegram.org/bots/api#passportelementerrorselfie
 */
class PassportElementErrorSelfie extends PassportElementError
{
    /**
     * Error source, must be selfie
     */
    #[Field('source', required: true)]
    private(set) PassportSource|string|null $source = null;

    /**
     * The section of the user's Telegram Passport which has the issue, one of "passport", "driver_license", "identity_card", "internal_passport"
     */
    #[Field('type', required: true)]
    private(set) PassportType|string|null $type = null;

    /**
     * Base64-encoded hash of the file with the selfie
     */
    #[Field('file_hash', required: true)]
    private(set) ?string $fileHash = null;

    /**
     * Error message
     */
    #[Field('message', required: true)]
    private(set) ?string $message = null;

}
