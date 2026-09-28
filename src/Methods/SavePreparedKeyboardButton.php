<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\KeyboardButton;
use Tueen\Telegram\Types\PreparedKeyboardButton;

/**
 * Stores a keyboard button that can be used by a user within a Mini App. Returns a PreparedKeyboardButton object.
 *
 * @link https://core.telegram.org/bots/api#savepreparedkeyboardbutton
 */
#[ApiMethod('savePreparedKeyboardButton', 'POST')]
#[ReturnType(PreparedKeyboardButton::class, isArray: false)]
class SavePreparedKeyboardButton extends Method
{
    /**
     * Unique identifier of the target user that can use the button
     */
    #[Field('user_id', required: true)]
    public int $userId;

    /**
     * A JSON-serialized object describing the button to be saved. The button must be of the type request_users, request_chat, or request_managed_bot.
     */
    #[Field('button', required: true)]
    public KeyboardButton $button;

    public function __construct(
        int $userId,
        KeyboardButton $button,
        mixed ...$extra
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($button !== null) $this->button = $button;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
