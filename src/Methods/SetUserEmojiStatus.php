<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Changes the emoji status for a given user that previously allowed the bot to manage their emoji status via the Mini App method requestEmojiStatusAccess. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setuseremojistatus
 */
#[ApiMethod('setUserEmojiStatus', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SetUserEmojiStatus extends Method
{
    /**
     * Unique identifier of the target user
     */
    #[Field('user_id', required: true)]
    public int $userId;

    /**
     * Custom emoji identifier of the emoji status to set. Pass an empty string to remove the status.
     */
    #[Field('emoji_status_custom_emoji_id', required: false)]
    public ?string $emojiStatusCustomEmojiId = null;

    /**
     * Expiration date of the emoji status, if any
     */
    #[Field('emoji_status_expiration_date', required: false)]
    public ?int $emojiStatusExpirationDate = null;

    public function __construct(
        int $userId,
        ?string $emojiStatusCustomEmojiId = null,
        ?int $emojiStatusExpirationDate = null,
        mixed ...$extra
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($emojiStatusCustomEmojiId !== null) $this->emojiStatusCustomEmojiId = $emojiStatusCustomEmojiId;
        if ($emojiStatusExpirationDate !== null) $this->emojiStatusExpirationDate = $emojiStatusExpirationDate;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
