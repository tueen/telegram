<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Types\MenuButton;

/**
 * Use this method to change the bot's menu button in a private chat, or the default menu button. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setchatmenubutton
 */
#[ApiMethod('setChatMenuButton', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SetChatMenuButton extends Method
{
    /**
     * Unique identifier for the target private chat. If not specified, the bot's default menu button will be changed.
     */
    #[Field('chat_id', required: false)]
    public ?int $chatId = null;

    /**
     * A JSON-serialized object for the bot's new menu button. Defaults to MenuButtonDefault.
     */
    #[Field('menu_button', required: false)]
    public ?MenuButton $menuButton = null;

    public function __construct(
        ?int $chatId = null,
        ?MenuButton $menuButton = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($menuButton !== null) $this->menuButton = $menuButton;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
