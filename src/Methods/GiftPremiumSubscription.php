<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Enums\ParseMode;

/**
 * Gifts a Telegram Premium subscription to the given user. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#giftpremiumsubscription
 */
#[ApiMethod('giftPremiumSubscription', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class GiftPremiumSubscription extends Method
{
    /**
     * Unique identifier of the target user who will receive a Telegram Premium subscription
     */
    #[Field('user_id', required: true)]
    public int $userId;

    /**
     * Number of months the Telegram Premium subscription will be active for the user; must be one of 3, 6, or 12
     */
    #[Field('month_count', required: true)]
    public int $monthCount;

    /**
     * Number of Telegram Stars to pay for the Telegram Premium subscription; must be 1000 for 3 months, 1500 for 6 months, and 2500 for 12 months
     */
    #[Field('star_count', required: true)]
    public int $starCount;

    /**
     * Text that will be shown along with the service message about the subscription; 0-128 characters
     */
    #[Field('text', required: false)]
    public ?string $text = null;

    /**
     * Mode for parsing entities in the text. See formatting options for more details. Entities other than "bold", "italic", "underline", "strikethrough", "spoiler", "custom_emoji", and "date_time" are ignored.
     */
    #[Field('text_parse_mode', required: false)]
    public ParseMode|string|null $textParseMode = null;

    /**
     * A JSON-serialized list of special entities that appear in the gift text. It can be specified instead of text_parse_mode. Entities other than "bold", "italic", "underline", "strikethrough", "spoiler", "custom_emoji", and "date_time" are ignored.
     */
    #[Field('text_entities', required: false)]
    public ?array $textEntities = null;

    public function __construct(
        int $userId,
        int $monthCount,
        int $starCount,
        ?string $text = null,
        ParseMode|string|null $textParseMode = null,
        ?array $textEntities = null,
        mixed ...$extra
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($monthCount !== null) $this->monthCount = $monthCount;
        if ($starCount !== null) $this->starCount = $starCount;
        if ($text !== null) $this->text = $text;
        if ($textParseMode !== null) $this->textParseMode = $textParseMode;
        if ($textEntities !== null) $this->textEntities = $textEntities;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
