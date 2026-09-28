<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a message about a scheduled giveaway.
 *
 * @link https://core.telegram.org/bots/api#giveaway
 */
class Giveaway extends Type
{
    /**
     * The list of chats which the user must join to participate in the giveaway
     * @var Chat[]|null
     */
    #[Field('chats', required: true)]
    #[ArrayOf(Chat::class)]
    private(set) ?array $chats = null;

    /**
     * Point in time (Unix timestamp) when winners of the giveaway will be selected
     */
    #[Field('winners_selection_date', required: true)]
    private(set) ?int $winnersSelectionDate = null;

    /**
     * The number of users which are supposed to be selected as winners of the giveaway
     */
    #[Field('winner_count', required: true)]
    private(set) ?int $winnerCount = null;

    /**
     * Optional. True, if only users who join the chats after the giveaway started should be eligible to win
     */
    #[Field('only_new_members', required: false)]
    private(set) ?bool $onlyNewMembers = null;

    /**
     * Optional. True, if the list of giveaway winners will be visible to everyone
     */
    #[Field('has_public_winners', required: false)]
    private(set) ?bool $hasPublicWinners = null;

    /**
     * Optional. Description of additional giveaway prize
     */
    #[Field('prize_description', required: false)]
    private(set) ?string $prizeDescription = null;

    /**
     * Optional. A list of two-letter ISO 3166-1 alpha-2 country codes indicating the countries from which eligible users for the giveaway must come. If empty, then all users can participate in the giveaway. Users with a phone number that was bought on Fragment can always participate in giveaways.
     * @var String[]|null
     */
    #[Field('country_codes', required: false)]
    private(set) ?array $countryCodes = null;

    /**
     * Optional. The number of Telegram Stars to be split between giveaway winners; for Telegram Star giveaways only
     */
    #[Field('prize_star_count', required: false)]
    private(set) ?int $prizeStarCount = null;

    /**
     * Optional. The number of months the Telegram Premium subscription won from the giveaway will be active for; for Telegram Premium giveaways only
     */
    #[Field('premium_subscription_month_count', required: false)]
    private(set) ?int $premiumSubscriptionMonthCount = null;

}
