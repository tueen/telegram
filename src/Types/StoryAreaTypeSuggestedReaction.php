<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\StoryAreaTypeType;

/**
 * Describes a story area pointing to a suggested reaction. Currently, a story can have up to 5 suggested reaction areas.
 *
 * @link https://core.telegram.org/bots/api#storyareatypesuggestedreaction
 */
class StoryAreaTypeSuggestedReaction extends StoryAreaType
{
    /**
     * Type of the area, always "suggested_reaction"
     */
    #[Field('type', required: true)]
    private(set) StoryAreaTypeType|string|null $type = null;

    /**
     * Type of the reaction
     */
    #[Field('reaction_type', required: true)]
    private(set) ?ReactionType $reactionType = null;

    /**
     * Optional. Pass True if the reaction area has a dark background
     */
    #[Field('is_dark', required: false)]
    private(set) ?bool $isDark = null;

    /**
     * Optional. Pass True if reaction area corner is flipped
     */
    #[Field('is_flipped', required: false)]
    private(set) ?bool $isFlipped = null;

}
