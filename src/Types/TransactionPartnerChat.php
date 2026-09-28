<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\TransactionPartnerType;

/**
 * Describes a transaction with a chat.
 *
 * @link https://core.telegram.org/bots/api#transactionpartnerchat
 */
class TransactionPartnerChat extends TransactionPartner
{
    /**
     * Type of the transaction partner, always "chat"
     */
    #[Field('type', required: true)]
    private(set) TransactionPartnerType|string|null $type = null;

    /**
     * Information about the chat
     */
    #[Field('chat', required: true)]
    private(set) ?Chat $chat = null;

    /**
     * Optional. The gift sent to the chat by the bot
     */
    #[Field('gift', required: false)]
    private(set) ?Gift $gift = null;

}
