<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\OwnedGifts;

/**
 * Returns the gifts owned and hosted by a user. Returns OwnedGifts on success.
 *
 * @link https://core.telegram.org/bots/api#getusergifts
 */
#[ApiMethod('getUserGifts', 'POST')]
#[ReturnType(OwnedGifts::class, isArray: false)]
class GetUserGifts extends Method
{
    /**
     * Unique identifier of the user
     */
    #[Field('user_id', required: true)]
    public int $userId;

    /**
     * Pass True to exclude gifts that can be purchased an unlimited number of times
     */
    #[Field('exclude_unlimited', required: false)]
    public ?bool $excludeUnlimited = null;

    /**
     * Pass True to exclude gifts that can be purchased a limited number of times and can be upgraded to unique
     */
    #[Field('exclude_limited_upgradable', required: false)]
    public ?bool $excludeLimitedUpgradable = null;

    /**
     * Pass True to exclude gifts that can be purchased a limited number of times and can't be upgraded to unique
     */
    #[Field('exclude_limited_non_upgradable', required: false)]
    public ?bool $excludeLimitedNonUpgradable = null;

    /**
     * Pass True to exclude gifts that were assigned from the TON blockchain and can't be resold or transferred in Telegram
     */
    #[Field('exclude_from_blockchain', required: false)]
    public ?bool $excludeFromBlockchain = null;

    /**
     * Pass True to exclude unique gifts
     */
    #[Field('exclude_unique', required: false)]
    public ?bool $excludeUnique = null;

    /**
     * Pass True to sort results by gift price instead of send date. Sorting is applied before pagination.
     */
    #[Field('sort_by_price', required: false)]
    public ?bool $sortByPrice = null;

    /**
     * Offset of the first entry to return as received from the previous request; use an empty string to get the first chunk of results
     */
    #[Field('offset', required: false)]
    public ?string $offset = null;

    /**
     * The maximum number of gifts to be returned; 1-100. Defaults to 100.
     */
    #[Field('limit', required: false)]
    public ?int $limit = null;

    public function __construct(
        int $userId,
        ?bool $excludeUnlimited = null,
        ?bool $excludeLimitedUpgradable = null,
        ?bool $excludeLimitedNonUpgradable = null,
        ?bool $excludeFromBlockchain = null,
        ?bool $excludeUnique = null,
        ?bool $sortByPrice = null,
        ?string $offset = null,
        ?int $limit = null
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($excludeUnlimited !== null) $this->excludeUnlimited = $excludeUnlimited;
        if ($excludeLimitedUpgradable !== null) $this->excludeLimitedUpgradable = $excludeLimitedUpgradable;
        if ($excludeLimitedNonUpgradable !== null) $this->excludeLimitedNonUpgradable = $excludeLimitedNonUpgradable;
        if ($excludeFromBlockchain !== null) $this->excludeFromBlockchain = $excludeFromBlockchain;
        if ($excludeUnique !== null) $this->excludeUnique = $excludeUnique;
        if ($sortByPrice !== null) $this->sortByPrice = $sortByPrice;
        if ($offset !== null) $this->offset = $offset;
        if ($limit !== null) $this->limit = $limit;
    }

    public static function make(
        int $userId,
        ?bool $excludeUnlimited = null,
        ?bool $excludeLimitedUpgradable = null,
        ?bool $excludeLimitedNonUpgradable = null,
        ?bool $excludeFromBlockchain = null,
        ?bool $excludeUnique = null,
        ?bool $sortByPrice = null,
        ?string $offset = null,
        ?int $limit = null
    ): static
    {
        return new static($userId, $excludeUnlimited, $excludeLimitedUpgradable, $excludeLimitedNonUpgradable, $excludeFromBlockchain, $excludeUnique, $sortByPrice, $offset, $limit);
    }
}
