<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\User;
use Tueen\Telegram\Types\Location;

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
    public private(set) string $resultId;

    /**
     * The user that chose the result
     */
    #[Field('from', required: true)]
    public private(set) User $from;

    /**
     * Optional. Sender location, only for bots that require user location
     */
    #[Field('location', required: false)]
    public private(set) ?Location $location = null;

    /**
     * Optional. Identifier of the sent inline message. Available only if there is an inline keyboard attached to the message. Will be also received in callback queries and can be used to edit the message.
     */
    #[Field('inline_message_id', required: false)]
    public private(set) ?string $inlineMessageId = null;

    /**
     * The query that was used to obtain the result
     */
    #[Field('query', required: true)]
    public private(set) string $query;

}
