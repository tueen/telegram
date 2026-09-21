<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\StoryAreaTypeType;
use Tueen\Telegram\Types\ReactionType;

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
    public private(set) StoryAreaTypeType|string $type;

    /**
     * Type of the reaction
     */
    #[Field('reaction_type', required: true)]
    public private(set) ReactionType $reactionType;

    /**
     * Optional. Pass True if the reaction area has a dark background
     */
    #[Field('is_dark', required: false)]
    public private(set) ?bool $isDark = null;

    /**
     * Optional. Pass True if reaction area corner is flipped
     */
    #[Field('is_flipped', required: false)]
    public private(set) ?bool $isFlipped = null;

}
