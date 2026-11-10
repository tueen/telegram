<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object describes a sticker on a TON wallet card.
 *
 * @link https://core.telegram.org/bots/api#tonwalletcardsticker
 */
class TonWalletCardSticker extends Type
{
    /**
     * Position of the sticker on the card
     */
    #[Field('position', required: true)]
    private(set) ?AreaPosition $position = null;

    /**
     * The NFT shown as the sticker
     */
    #[Field('nft', required: true)]
    private(set) ?TonNft $nft = null;

}
