<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * Represents a result of an inline query that was chosen by the user and sent to their chat partner.
 * Note: It is necessary to enable inline feedback via @BotFather in order to receive these objects in updates.
 *
 * @link https://core.telegram.org/bots/api#choseninlineresult
 */
class ChosenInlineResult extends Type
{
    /**
     * The unique identifier for the result that was chosen
     */
    #[Field('result_id', required: true)]
    private(set) string $resultId;

    /**
     * The user that chose the result
     */
    #[Field('from', required: true)]
    private(set) User $from;

    /**
     * Optional. Sender location, only for bots that require user location
     */
    #[Field('location', required: false)]
    private(set) ?Location $location = null;

    /**
     * Optional. Identifier of the sent inline message. Available only if there is an inline keyboard attached to the message. Will be also received in callback queries and can be used to edit the message.
     */
    #[Field('inline_message_id', required: false)]
    private(set) ?string $inlineMessageId = null;

    /**
     * The query that was used to obtain the result
     */
    #[Field('query', required: true)]
    private(set) string $query;

}
