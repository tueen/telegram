<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object describes the source of a transaction, or its recipient for outgoing transactions. Currently, it can be one of
 * - TransactionPartnerUser
 * - TransactionPartnerChat
 * - TransactionPartnerAffiliateProgram
 * - TransactionPartnerFragment
 * - TransactionPartnerTelegramAds
 * - TransactionPartnerTelegramApi
 * - TransactionPartnerOther
 *
 * @link https://core.telegram.org/bots/api#transactionpartner
 */
class TransactionPartner extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {
        if (($data['type'] ?? '') === 'user') return TransactionPartnerUser::class;
        if (($data['type'] ?? '') === 'chat') return TransactionPartnerChat::class;
        if (($data['type'] ?? '') === 'affiliate_program') return TransactionPartnerAffiliateProgram::class;
        if (($data['type'] ?? '') === 'fragment') return TransactionPartnerFragment::class;
        if (($data['type'] ?? '') === 'telegram_ads') return TransactionPartnerTelegramAds::class;
        if (($data['type'] ?? '') === 'telegram_api') return TransactionPartnerTelegramApi::class;
        if (($data['type'] ?? '') === 'other') return TransactionPartnerOther::class;
        return static::class;
    }
}
