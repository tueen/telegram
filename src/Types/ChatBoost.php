<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object contains information about a chat boost.
 *
 * @link https://core.telegram.org/bots/api#chatboost
 */
class ChatBoost extends Type
{
    /**
     * Unique identifier of the boost
     */
    #[Field('boost_id', required: true)]
    private(set) ?string $boostId = null;

    /**
     * Point in time (Unix timestamp) when the chat was boosted
     */
    #[Field('add_date', required: true)]
    private(set) ?int $addDate = null;

    /**
     * Point in time (Unix timestamp) when the boost will automatically expire, unless the booster's Telegram Premium subscription is prolonged
     */
    #[Field('expiration_date', required: true)]
    private(set) ?int $expirationDate = null;

    /**
     * Source of the added boost
     */
    #[Field('source', required: true)]
    private(set) ?ChatBoostSource $source = null;

}
