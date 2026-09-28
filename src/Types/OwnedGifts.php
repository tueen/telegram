<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Contains the list of gifts received and owned by a user or a chat.
 *
 * @link https://core.telegram.org/bots/api#ownedgifts
 */
class OwnedGifts extends Type
{
    /**
     * The total number of gifts owned by the user or the chat
     */
    #[Field('total_count', required: true)]
    private(set) ?int $totalCount = null;

    /**
     * The list of gifts
     * @var OwnedGift[]|null
     */
    #[Field('gifts', required: true)]
    #[ArrayOf(OwnedGift::class)]
    private(set) ?array $gifts = null;

    /**
     * Optional. Offset for the next request. If empty, then there are no more results.
     */
    #[Field('next_offset', required: false)]
    private(set) ?string $nextOffset = null;

}
