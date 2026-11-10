<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object describes a TON wallet card, which is shown on the profile page of a user to everyone.
 *
 * @link https://core.telegram.org/bots/api#tonwalletcard
 */
class TonWalletCard extends Type
{
    /**
     * Address of the wallet
     */
    #[Field('address', required: true)]
    private(set) ?string $address = null;

    /**
     * Balance of the wallet, in the smallest units of the cryptocurrency, as a decimal number
     */
    #[Field('amount', required: true)]
    private(set) ?string $amount = null;

    /**
     * Stickers placed on the card
     * @var TonWalletCardSticker[]|null
     */
    #[Field('stickers', required: true)]
    #[ArrayOf(TonWalletCardSticker::class)]
    private(set) ?array $stickers = null;

    /**
     * Optional. Address of the NFT of one of the stickers, whose colors must be used for the card background; omitted if the default background must be used
     */
    #[Field('background_nft_address', required: false)]
    private(set) ?string $backgroundNftAddress = null;

}
