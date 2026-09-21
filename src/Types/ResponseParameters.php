<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Describes why a request was unsuccessful.
 *
 * @link https://core.telegram.org/bots/api#responseparameters
 */
class ResponseParameters extends Type
{
    /**
     * Optional. The group has been migrated to a supergroup with the specified identifier. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this identifier.
     */
    #[Field('migrate_to_chat_id', required: false)]
    public private(set) ?int $migrateToChatId = null;

    /**
     * Optional. In case of exceeding flood control, the number of seconds left to wait before the request can be repeated
     */
    #[Field('retry_after', required: false)]
    public private(set) ?int $retryAfter = null;

}
