<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Sends a gift to the given user or channel chat. The gift can't be converted to Telegram Stars by the receiver. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#sendgift
 */
#[ApiMethod('sendGift', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SendGift extends Method
{
    /**
     * Identifier of the gift; limited gifts can't be sent to channel chats
     */
    #[Field('gift_id', required: true)]
    public string $giftId;

    /**
     * Required if chat_id is not specified. Unique identifier of the target user who will receive the gift.
     */
    #[Field('user_id', required: false)]
    public ?int $userId = null;

    /**
     * Required if user_id is not specified. Unique identifier for the chat or username of the channel (in the format @username) that will receive the gift.
     */
    #[Field('chat_id', required: false)]
    public int|string|null $chatId = null;

    /**
     * Pass True to pay for the gift upgrade from the bot's balance, thereby making the upgrade free for the receiver
     */
    #[Field('pay_for_upgrade', required: false)]
    public ?bool $payForUpgrade = null;

    /**
     * Text that will be shown along with the gift; 0-128 characters
     */
    #[Field('text', required: false)]
    public ?string $text = null;

    /**
     * Mode for parsing entities in the text. See formatting options for more details. Entities other than "bold", "italic", "underline", "strikethrough", "spoiler", "custom_emoji", and "date_time" are ignored.
     */
    #[Field('text_parse_mode', required: false)]
    public ?string $textParseMode = null;

    /**
     * A JSON-serialized list of special entities that appear in the gift text. It can be specified instead of text_parse_mode. Entities other than "bold", "italic", "underline", "strikethrough", "spoiler", "custom_emoji", and "date_time" are ignored.
     */
    #[Field('text_entities', required: false)]
    public ?array $textEntities = null;

    public function __construct(
        string $giftId,
        ?int $userId = null,
        int|string|null $chatId = null,
        ?bool $payForUpgrade = null,
        ?string $text = null,
        ?string $textParseMode = null,
        ?array $textEntities = null
    )
    {
        if ($giftId !== null) $this->giftId = $giftId;
        if ($userId !== null) $this->userId = $userId;
        if ($chatId !== null) $this->chatId = $chatId;
        if ($payForUpgrade !== null) $this->payForUpgrade = $payForUpgrade;
        if ($text !== null) $this->text = $text;
        if ($textParseMode !== null) $this->textParseMode = $textParseMode;
        if ($textEntities !== null) $this->textEntities = $textEntities;
    }

    public static function make(
        string $giftId,
        ?int $userId = null,
        int|string|null $chatId = null,
        ?bool $payForUpgrade = null,
        ?string $text = null,
        ?string $textParseMode = null,
        ?array $textEntities = null
    ): static
    {
        return new static($giftId, $userId, $chatId, $payForUpgrade, $text, $textParseMode, $textEntities);
    }
}
