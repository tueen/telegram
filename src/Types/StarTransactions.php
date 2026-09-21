<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Contains a list of Telegram Star transactions.
 *
 * @link https://core.telegram.org/bots/api#startransactions
 */
class StarTransactions extends Type
{
    /**
     * The list of transactions
     * @var StarTransaction[]|null
     */
    #[Field('transactions', required: true)]
    #[ArrayOf(StarTransaction::class)]
    public private(set) array $transactions;

}
