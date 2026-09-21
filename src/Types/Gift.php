<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\Sticker;
use Tueen\Telegram\Types\GiftBackground;
use Tueen\Telegram\Types\Chat;

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
    public private(set) string $id;

    /**
     * The sticker that represents the gift
     */
    #[Field('sticker', required: true)]
    public private(set) Sticker $sticker;

    /**
     * The number of Telegram Stars that must be paid to send the sticker
     */
    #[Field('star_count', required: true)]
    public private(set) int $starCount;

    /**
     * Optional. The number of Telegram Stars that must be paid to upgrade the gift to a unique one
     */
    #[Field('upgrade_star_count', required: false)]
    public private(set) ?int $upgradeStarCount = null;

    /**
     * Optional. True, if the gift can only be purchased by Telegram Premium subscribers
     */
    #[Field('is_premium', required: false)]
    public private(set) ?bool $isPremium = null;

    /**
     * Optional. True, if the gift can be used (after being upgraded) to customize a user's appearance
     */
    #[Field('has_colors', required: false)]
    public private(set) ?bool $hasColors = null;

    /**
     * Optional. The total number of gifts of this type that can be sent by all users; for limited gifts only
     */
    #[Field('total_count', required: false)]
    public private(set) ?int $totalCount = null;

    /**
     * Optional. The number of remaining gifts of this type that can be sent by all users; for limited gifts only
     */
    #[Field('remaining_count', required: false)]
    public private(set) ?int $remainingCount = null;

    /**
     * Optional. The total number of gifts of this type that can be sent by the bot; for limited gifts only
     */
    #[Field('personal_total_count', required: false)]
    public private(set) ?int $personalTotalCount = null;

    /**
     * Optional. The number of remaining gifts of this type that can be sent by the bot; for limited gifts only
     */
    #[Field('personal_remaining_count', required: false)]
    public private(set) ?int $personalRemainingCount = null;

    /**
     * Optional. Background of the gift
     */
    #[Field('background', required: false)]
    public private(set) ?GiftBackground $background = null;

    /**
     * Optional. The total number of different unique gifts that can be obtained by upgrading the gift
     */
    #[Field('unique_gift_variant_count', required: false)]
    public private(set) ?int $uniqueGiftVariantCount = null;

    /**
     * Optional. Information about the chat that published the gift
     */
    #[Field('publisher_chat', required: false)]
    public private(set) ?Chat $publisherChat = null;

}
