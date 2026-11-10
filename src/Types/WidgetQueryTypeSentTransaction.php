<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * The query notifies the bot about a transaction sent from a TON wallet of the user who sent the query.
 *
 * @link https://core.telegram.org/bots/api#widgetquerytypesenttransaction
 */
class WidgetQueryTypeSentTransaction extends WidgetQueryType
{
    /**
     * Type of the query, always "sent_transaction"
     */
    #[Field('type', required: true)]
    private(set) ?string $type = null;

    /**
     * The wallet from which the transaction was sent
     */
    #[Field('wallet', required: true)]
    private(set) ?WidgetQueryWallet $wallet = null;

    /**
     * Value of msg_hash of the transaction
     */
    #[Field('msg_hash', required: true)]
    private(set) ?string $msgHash = null;

}
