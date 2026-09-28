<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * Describes an inline message to be sent by a user of a Mini App.
 *
 * @link https://core.telegram.org/bots/api#preparedinlinemessage
 */
class PreparedInlineMessage extends Type
{
    /**
     * Unique identifier of the prepared message
     */
    #[Field('id', required: true)]
    private(set) ?string $id = null;

    /**
     * Expiration date of the prepared message, in Unix time. Expired prepared messages can no longer be used.
     */
    #[Field('expiration_date', required: true)]
    private(set) ?int $expirationDate = null;

}
