<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * The query contains data signed by a TON wallet of the user who sent the query. The data is signed for the domain bot<bot_id>, where <bot_id> is the identifier of the bot.
 *
 * @link https://core.telegram.org/bots/api#widgetquerytypesigndata
 */
class WidgetQueryTypeSignData extends WidgetQueryType
{
    /**
     * Type of the query, always "sign_data"
     */
    #[Field('type', required: true)]
    private(set) ?string $type = null;

    /**
     * The wallet that signed the data
     */
    #[Field('wallet', required: true)]
    private(set) ?WidgetQueryWallet $wallet = null;

    /**
     * JSON-serialized signed data
     */
    #[Field('sign_data', required: true)]
    private(set) ?string $signData = null;

}
