<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object contains information about the color scheme for a user's name, message replies and link previews based on a unique gift.
 *
 * @link https://core.telegram.org/bots/api#uniquegiftcolors
 */
class UniqueGiftColors extends Type
{
    /**
     * Custom emoji identifier of the unique gift's model
     */
    #[Field('model_custom_emoji_id', required: true)]
    private(set) ?string $modelCustomEmojiId = null;

    /**
     * Custom emoji identifier of the unique gift's symbol
     */
    #[Field('symbol_custom_emoji_id', required: true)]
    private(set) ?string $symbolCustomEmojiId = null;

    /**
     * Main color used in light themes; RGB format
     */
    #[Field('light_theme_main_color', required: true)]
    private(set) ?int $lightThemeMainColor = null;

    /**
     * List of 1-3 additional colors used in light themes; RGB format
     * @var Integer[]|null
     */
    #[Field('light_theme_other_colors', required: true)]
    private(set) ?array $lightThemeOtherColors = null;

    /**
     * Main color used in dark themes; RGB format
     */
    #[Field('dark_theme_main_color', required: true)]
    private(set) ?int $darkThemeMainColor = null;

    /**
     * List of 1-3 additional colors used in dark themes; RGB format
     * @var Integer[]|null
     */
    #[Field('dark_theme_other_colors', required: true)]
    private(set) ?array $darkThemeOtherColors = null;

}
