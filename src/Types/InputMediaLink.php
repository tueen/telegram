<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputMediaType;

/**
 * Represents an HTTP link to be sent.
 *
 * @link https://core.telegram.org/bots/api#inputmedialink
 */
class InputMediaLink extends InputPollOptionMedia
{
    /**
     * Type of the media, must be link
     */
    #[Field('type', required: true)]
    public private(set) InputMediaType|string $type;

    /**
     * HTTP URL of the link
     */
    #[Field('url', required: true)]
    public private(set) string $url;

}
