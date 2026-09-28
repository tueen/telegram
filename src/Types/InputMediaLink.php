<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

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
    private(set) InputMediaType|string|null $type = null;

    /**
     * HTTP URL of the link
     */
    #[Field('url', required: true)]
    private(set) ?string $url = null;

}
