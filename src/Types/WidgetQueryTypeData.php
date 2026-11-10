<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * The query contains data for the bot.
 *
 * @link https://core.telegram.org/bots/api#widgetquerytypedata
 */
class WidgetQueryTypeData extends WidgetQueryType
{
    /**
     * Type of the query, always "data"
     */
    #[Field('type', required: true)]
    private(set) ?string $type = null;

    /**
     * Optional. TON wallet card of the user who sent the query, if it was sent to the bot along with the query
     */
    #[Field('wallet_card', required: false)]
    private(set) ?TonWalletCard $walletCard = null;

}
