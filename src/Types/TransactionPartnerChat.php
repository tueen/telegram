<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
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
    public private(set) TransactionPartnerType|string $type;

    /**
     * Information about the chat
     */
    #[Field('chat', required: true)]
    public private(set) Chat $chat;

    /**
     * Optional. The gift sent to the chat by the bot
     */
    #[Field('gift', required: false)]
    public private(set) ?Gift $gift = null;

}
