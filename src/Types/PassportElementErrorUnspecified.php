<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
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
    public private(set) PassportSource|string $source;

    /**
     * Type of element of the user's Telegram Passport which has the issue
     */
    #[Field('type', required: true)]
    public private(set) PassportType|string $type;

    /**
     * Base64-encoded element hash
     */
    #[Field('element_hash', required: true)]
    public private(set) string $elementHash;

    /**
     * Error message
     */
    #[Field('message', required: true)]
    public private(set) string $message;

}
