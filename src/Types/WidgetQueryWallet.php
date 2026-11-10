<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object describes a TON wallet of the user, which is used in a query from a widget.
 *
 * @link https://core.telegram.org/bots/api#widgetquerywallet
 */
class WidgetQueryWallet extends Type
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
     * Optional. Hex-encoded public key of the wallet; omitted if unknown
     */
    #[Field('public_key', required: false)]
    private(set) ?string $publicKey = null;

    /**
     * Optional. Base64-encoded serialized StateInit of the wallet contract; omitted if and only if public_key is omitted
     */
    #[Field('state_init', required: false)]
    private(set) ?string $stateInit = null;

}
