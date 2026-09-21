<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a block in a rich formatted message to be sent. Currently, it can be any of the following types:
 * - InputRichBlockParagraph
 * - InputRichBlockSectionHeading
 * - InputRichBlockPreformatted
 * - InputRichBlockFooter
 * - InputRichBlockDivider
 * - InputRichBlockMathematicalExpression
 * - InputRichBlockAnchor
 * - InputRichBlockList
 * - InputRichBlockBlockQuotation
 * - InputRichBlockExpandableBlockQuotation
 * - InputRichBlockPullQuotation
 * - InputRichBlockCollage
 * - InputRichBlockSlideshow
 * - InputRichBlockTable
 * - InputRichBlockDetails
 * - InputRichBlockMap
 * - InputRichBlockButtons
 * - InputRichBlockAnimation
 * - InputRichBlockAudio
 * - InputRichBlockDocument
 * - InputRichBlockPhoto
 * - InputRichBlockVideo
 * - InputRichBlockVoiceNote
 * - InputRichBlockThinking
 *
 * @link https://core.telegram.org/bots/api#inputrichblock
 */
class InputRichBlock extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {
        if (($data['type'] ?? '') === 'paragraph') return InputRichBlockParagraph::class;
        if (($data['type'] ?? '') === 'heading') return InputRichBlockSectionHeading::class;
        if (($data['type'] ?? '') === 'pre') return InputRichBlockPreformatted::class;
        if (($data['type'] ?? '') === 'footer') return InputRichBlockFooter::class;
        if (($data['type'] ?? '') === 'divider') return InputRichBlockDivider::class;
        if (($data['type'] ?? '') === 'mathematical_expression') return InputRichBlockMathematicalExpression::class;
        if (($data['type'] ?? '') === 'anchor') return InputRichBlockAnchor::class;
        if (($data['type'] ?? '') === 'list') return InputRichBlockList::class;
        if (($data['type'] ?? '') === 'blockquote') return InputRichBlockBlockQuotation::class;
        if (($data['type'] ?? '') === 'expandable_blockquote') return InputRichBlockExpandableBlockQuotation::class;
        if (($data['type'] ?? '') === 'pullquote') return InputRichBlockPullQuotation::class;
        if (($data['type'] ?? '') === 'collage') return InputRichBlockCollage::class;
        if (($data['type'] ?? '') === 'slideshow') return InputRichBlockSlideshow::class;
        if (($data['type'] ?? '') === 'table') return InputRichBlockTable::class;
        if (($data['type'] ?? '') === 'details') return InputRichBlockDetails::class;
        if (($data['type'] ?? '') === 'map') return InputRichBlockMap::class;
        if (($data['type'] ?? '') === 'buttons') return InputRichBlockButtons::class;
        if (($data['type'] ?? '') === 'animation') return InputRichBlockAnimation::class;
        if (($data['type'] ?? '') === 'audio') return InputRichBlockAudio::class;
        if (($data['type'] ?? '') === 'document') return InputRichBlockDocument::class;
        if (($data['type'] ?? '') === 'photo') return InputRichBlockPhoto::class;
        if (($data['type'] ?? '') === 'video') return InputRichBlockVideo::class;
        if (($data['type'] ?? '') === 'voice_note') return InputRichBlockVoiceNote::class;
        if (($data['type'] ?? '') === 'thinking') return InputRichBlockThinking::class;
        return static::class;
    }
}
