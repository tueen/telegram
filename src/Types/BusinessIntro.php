<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * Contains information about the start page settings of a Telegram Business account.
 *
 * @link https://core.telegram.org/bots/api#businessintro
 */
class BusinessIntro extends Type
{
    /**
     * Optional. Title text of the business intro
     */
    #[Field('title', required: false)]
    private(set) ?string $title = null;

    /**
     * Optional. Message text of the business intro
     */
    #[Field('message', required: false)]
    private(set) ?string $message = null;

    /**
     * Optional. Sticker of the business intro
     */
    #[Field('sticker', required: false)]
    private(set) ?Sticker $sticker = null;

}
