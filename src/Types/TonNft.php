<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object represents an NFT in the TON blockchain.
 *
 * @link https://core.telegram.org/bots/api#tonnft
 */
class TonNft extends Type
{
    /**
     * Address of the NFT
     */
    #[Field('address', required: true)]
    private(set) ?string $address = null;

    /**
     * Optional. Address of the collection to which the NFT belongs; omitted if unknown
     */
    #[Field('collection_address', required: false)]
    private(set) ?string $collectionAddress = null;

    /**
     * Index of the NFT among all NFTs from the collection as a decimal number
     */
    #[Field('index', required: true)]
    private(set) ?string $index = null;

    /**
     * Name of the NFT
     */
    #[Field('name', required: true)]
    private(set) ?string $name = null;

    /**
     * Description of the NFT
     */
    #[Field('description', required: true)]
    private(set) ?string $description = null;

    /**
     * Optional. Attributes of the NFT
     * @var TonNftAttribute[]|null
     */
    #[Field('attributes', required: false)]
    #[ArrayOf(TonNftAttribute::class)]
    private(set) ?array $attributes = null;

    /**
     * Optional. The unique gift corresponding to the NFT
     */
    #[Field('gift', required: false)]
    private(set) ?UniqueGift $gift = null;

}
