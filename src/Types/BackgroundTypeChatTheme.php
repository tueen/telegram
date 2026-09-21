<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\BackgroundTypeType;

/**
 * The background is taken directly from a built-in chat theme.
 *
 * @link https://core.telegram.org/bots/api#backgroundtypechattheme
 */
class BackgroundTypeChatTheme extends BackgroundType
{
    /**
     * Type of the background, always "chat_theme"
     */
    #[Field('type', required: true)]
    public private(set) BackgroundTypeType|string $type;

    /**
     * Name of the chat theme, which is usually an emoji
     */
    #[Field('theme_name', required: true)]
    public private(set) string $themeName;

}
