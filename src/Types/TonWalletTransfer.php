<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * Describes a service message about a transfer with a TON wallet.
 *
 * @link https://core.telegram.org/bots/api#tonwallettransfer
 */
class TonWalletTransfer extends Type
{
    /**
     * Unique identifier of the transaction; may be empty if the transfer hasn't been sent yet
     */
    #[Field('transaction_id', required: true)]
    private(set) ?string $transactionId = null;

    /**
     * Address of the transfer peer in the TON blockchain
     */
    #[Field('peer_address', required: true)]
    private(set) ?string $peerAddress = null;

    /**
     * The amount of transferred cryptocurrency, in the smallest units of the cryptocurrency, as a decimal number; negative for outgoing transfers
     */
    #[Field('amount', required: true)]
    private(set) ?string $amount = null;

    /**
     * Optional. Comment added to the transfer; omitted if the comment is encrypted
     */
    #[Field('comment', required: false)]
    private(set) ?string $comment = null;

}
