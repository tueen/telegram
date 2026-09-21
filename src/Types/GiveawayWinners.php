<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\Chat;
use Tueen\Telegram\Types\User;

/**
 * This object represents a message about the completion of a giveaway with public winners.
 *
 * @link https://core.telegram.org/bots/api#giveawaywinners
 */
class GiveawayWinners extends Type
{
    /**
     * The chat that created the giveaway
     */
    #[Field('chat', required: true)]
    public private(set) Chat $chat;

    /**
     * Identifier of the message with the giveaway in the chat
     */
    #[Field('giveaway_message_id', required: true)]
    public private(set) int $giveawayMessageId;

    /**
     * Point in time (Unix timestamp) when winners of the giveaway were selected
     */
    #[Field('winners_selection_date', required: true)]
    public private(set) int $winnersSelectionDate;

    /**
     * Total number of winners in the giveaway
     */
    #[Field('winner_count', required: true)]
    public private(set) int $winnerCount;

    /**
     * List of up to 100 winners of the giveaway
     * @var User[]|null
     */
    #[Field('winners', required: true)]
    #[ArrayOf(User::class)]
    public private(set) array $winners;

    /**
     * Optional. The number of other chats the user had to join in order to be eligible for the giveaway
     */
    #[Field('additional_chat_count', required: false)]
    public private(set) ?int $additionalChatCount = null;

    /**
     * Optional. The number of Telegram Stars that were split between giveaway winners; for Telegram Star giveaways only
     */
    #[Field('prize_star_count', required: false)]
    public private(set) ?int $prizeStarCount = null;

    /**
     * Optional. The number of months the Telegram Premium subscription won from the giveaway will be active for; for Telegram Premium giveaways only
     */
    #[Field('premium_subscription_month_count', required: false)]
    public private(set) ?int $premiumSubscriptionMonthCount = null;

    /**
     * Optional. Number of undistributed prizes
     */
    #[Field('unclaimed_prize_count', required: false)]
    public private(set) ?int $unclaimedPrizeCount = null;

    /**
     * Optional. True, if only users who had joined the chats after the giveaway started were eligible to win
     */
    #[Field('only_new_members', required: false)]
    public private(set) ?bool $onlyNewMembers = null;

    /**
     * Optional. True, if the giveaway was canceled because the payment for it was refunded
     */
    #[Field('was_refunded', required: false)]
    public private(set) ?bool $wasRefunded = null;

    /**
     * Optional. Description of additional giveaway prize
     */
    #[Field('prize_description', required: false)]
    public private(set) ?string $prizeDescription = null;

}
