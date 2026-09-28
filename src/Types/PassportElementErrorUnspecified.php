<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\PassportSource;
use Tueen\Telegram\Enums\PassportType;

/**
 * Represents an issue in an unspecified place. The error is considered resolved when new data is added.
 *
 * @link https://core.telegram.org/bots/api#passportelementerrorunspecified
 */
class PassportElementErrorUnspecified extends PassportElementError
{
    /**
     * Error source, must be unspecified
     */
    #[Field('source', required: true)]
    private(set) PassportSource|string|null $source = null;

    /**
     * Type of element of the user's Telegram Passport which has the issue
     */
    #[Field('type', required: true)]
    private(set) PassportType|string|null $type = null;

    /**
     * Base64-encoded element hash
     */
    #[Field('element_hash', required: true)]
    private(set) ?string $elementHash = null;

    /**
     * Error message
     */
    #[Field('message', required: true)]
    private(set) ?string $message = null;

}
