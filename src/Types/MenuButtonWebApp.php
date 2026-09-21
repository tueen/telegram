<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\MenuButtonType;

/**
 * Represents a menu button, which launches a Web App.
 *
 * @link https://core.telegram.org/bots/api#menubuttonwebapp
 */
class MenuButtonWebApp extends MenuButton
{
    /**
     * Type of the button, must be web_app
     */
    #[Field('type', required: true)]
    public private(set) MenuButtonType|string $type;

    /**
     * Text on the button
     */
    #[Field('text', required: true)]
    public private(set) string $text;

    /**
     * Description of the Web App that will be launched when the user presses the button. The Web App will be able to send an arbitrary message on behalf of the user using the method answerWebAppQuery. Alternatively, a t.me link to a Web App of the bot can be specified in the object instead of the Web App's URL, in which case the Web App will be opened as if the user pressed the link.
     */
    #[Field('web_app', required: true)]
    public private(set) WebAppInfo $webApp;

}
