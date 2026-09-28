<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\PassportSource;
use Tueen\Telegram\Enums\PassportType;

/**
 * Represents an issue in one of the data fields that was provided by the user. The error is considered resolved when the field's value changes.
 *
 * @link https://core.telegram.org/bots/api#passportelementerrordatafield
 */
class PassportElementErrorDataField extends PassportElementError
{
    /**
     * Error source, must be data
     */
    #[Field('source', required: true)]
    private(set) PassportSource|string $source;

    /**
     * The section of the user's Telegram Passport which has the error, one of "personal_details", "passport", "driver_license", "identity_card", "internal_passport", "address"
     */
    #[Field('type', required: true)]
    private(set) PassportType|string $type;

    /**
     * Name of the data field which has the error
     */
    #[Field('field_name', required: true)]
    private(set) string $fieldName;

    /**
     * Base64-encoded data hash
     */
    #[Field('data_hash', required: true)]
    private(set) string $dataHash;

    /**
     * Error message
     */
    #[Field('message', required: true)]
    private(set) string $message;

}
