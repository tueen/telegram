<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a gift that can be sent by the bot.
 *
 * @link https://core.telegram.org/bots/api#gift
 */
class Gift extends Type
{
    /**
     * Unique identifier of the gift
     */
    #[Field('id', required: true)]
    private(set) ?string $id = null;

    /**
     * The sticker that represents the gift
     */
    #[Field('sticker', required: true)]
    private(set) ?Sticker $sticker = null;

    /**
     * The number of Telegram Stars that must be paid to send the sticker
     */
    #[Field('star_count', required: true)]
    private(set) ?int $starCount = null;

    /**
     * Optional. The number of Telegram Stars that must be paid to upgrade the gift to a unique one
     */
    #[Field('upgrade_star_count', required: false)]
    private(set) ?int $upgradeStarCount = null;

    /**
     * Optional. True, if the gift can only be purchased by Telegram Premium subscribers
     */
    #[Field('is_premium', required: false)]
    private(set) ?bool $isPremium = null;

    /**
     * Optional. True, if the gift can be used (after being upgraded) to customize a user's appearance
     */
    #[Field('has_colors', required: false)]
    private(set) ?bool $hasColors = null;

    /**
     * Optional. The total number of gifts of this type that can be sent by all users; for limited gifts only
     */
    #[Field('total_count', required: false)]
    private(set) ?int $totalCount = null;

    /**
     * Optional. The number of remaining gifts of this type that can be sent by all users; for limited gifts only
     */
    #[Field('remaining_count', required: false)]
    private(set) ?int $remainingCount = null;

    /**
     * Optional. The total number of gifts of this type that can be sent by the bot; for limited gifts only
     */
    #[Field('personal_total_count', required: false)]
    private(set) ?int $personalTotalCount = null;

    /**
     * Optional. The number of remaining gifts of this type that can be sent by the bot; for limited gifts only
     */
    #[Field('personal_remaining_count', required: false)]
    private(set) ?int $personalRemainingCount = null;

    /**
     * Optional. Background of the gift
     */
    #[Field('background', required: false)]
    private(set) ?GiftBackground $background = null;

    /**
     * Optional. The total number of different unique gifts that can be obtained by upgrading the gift
     */
    #[Field('unique_gift_variant_count', required: false)]
    private(set) ?int $uniqueGiftVariantCount = null;

    /**
     * Optional. Information about the chat that published the gift
     */
    #[Field('publisher_chat', required: false)]
    private(set) ?Chat $publisherChat = null;

}
