<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * The theme with emoji placed on a background.
 *
 * @link https://core.telegram.org/bots/api#chatsharethemeemoji
 */
class ChatShareThemeEmoji extends ChatShareTheme
{
    /**
     * Type of the theme, always "emoji"
     */
    #[Field('type', required: true)]
    private(set) ?string $type = null;

    /**
     * The emoji; 0-6 regular or custom emoji
     * @var EmojiType[]|null
     */
    #[Field('emojis', required: true)]
    #[ArrayOf(EmojiType::class)]
    private(set) ?array $emojis = null;

    /**
     * Optional. Identifier of the backdrop used as the background; omitted if the default background is used. Use getChatShareThemeBackdrops to get details about the backdrop.
     */
    #[Field('background_backdrop_id', required: false)]
    private(set) ?int $backgroundBackdropId = null;

}
