<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Rich formatted message.
 *
 * @link https://core.telegram.org/bots/api#richmessage
 */
class RichMessage extends Type
{
    /**
     * Content of the message
     * @var RichBlock[]|null
     */
    #[Field('blocks', required: true)]
    #[ArrayOf(RichBlock::class)]
    private(set) ?array $blocks = null;

    /**
     * Optional. True, if the rich message must be shown right-to-left
     */
    #[Field('is_rtl', required: false)]
    private(set) ?bool $isRtl = null;

}
