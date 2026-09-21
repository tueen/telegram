<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Represents an HTTP link.
 *
 * @link https://core.telegram.org/bots/api#link
 */
class Link extends Type
{
    /**
     * URL of the link
     */
    #[Field('url', required: true)]
    public private(set) string $url;

}
