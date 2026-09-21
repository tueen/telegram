<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\ChatBoostSource;

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
    public private(set) string $boostId;

    /**
     * Point in time (Unix timestamp) when the chat was boosted
     */
    #[Field('add_date', required: true)]
    public private(set) int $addDate;

    /**
     * Point in time (Unix timestamp) when the boost will automatically expire, unless the booster's Telegram Premium subscription is prolonged
     */
    #[Field('expiration_date', required: true)]
    public private(set) int $expirationDate;

    /**
     * Source of the added boost
     */
    #[Field('source', required: true)]
    public private(set) ChatBoostSource $source;

}
