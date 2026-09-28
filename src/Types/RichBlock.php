<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

/**
 * This object represents a block in a rich formatted message. Currently, it can be any of the following types:
 * - RichBlockParagraph
 * - RichBlockSectionHeading
 * - RichBlockPreformatted
 * - RichBlockFooter
 * - RichBlockDivider
 * - RichBlockMathematicalExpression
 * - RichBlockAnchor
 * - RichBlockList
 * - RichBlockBlockQuotation
 * - RichBlockExpandableBlockQuotation
 * - RichBlockPullQuotation
 * - RichBlockCollage
 * - RichBlockSlideshow
 * - RichBlockTable
 * - RichBlockDetails
 * - RichBlockMap
 * - RichBlockButtons
 * - RichBlockAnimation
 * - RichBlockAudio
 * - RichBlockDocument
 * - RichBlockPhoto
 * - RichBlockVideo
 * - RichBlockVoiceNote
 * - RichBlockThinking
 *
 * @link https://core.telegram.org/bots/api#richblock
 */
class RichBlock extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {
        if (($data['type'] ?? '') === 'paragraph') return RichBlockParagraph::class;
        if (($data['type'] ?? '') === 'heading') return RichBlockSectionHeading::class;
        if (($data['type'] ?? '') === 'pre') return RichBlockPreformatted::class;
        if (($data['type'] ?? '') === 'footer') return RichBlockFooter::class;
        if (($data['type'] ?? '') === 'divider') return RichBlockDivider::class;
        if (($data['type'] ?? '') === 'mathematical_expression') return RichBlockMathematicalExpression::class;
        if (($data['type'] ?? '') === 'anchor') return RichBlockAnchor::class;
        if (($data['type'] ?? '') === 'list') return RichBlockList::class;
        if (($data['type'] ?? '') === 'blockquote') return RichBlockBlockQuotation::class;
        if (($data['type'] ?? '') === 'expandable_blockquote') return RichBlockExpandableBlockQuotation::class;
        if (($data['type'] ?? '') === 'pullquote') return RichBlockPullQuotation::class;
        if (($data['type'] ?? '') === 'collage') return RichBlockCollage::class;
        if (($data['type'] ?? '') === 'slideshow') return RichBlockSlideshow::class;
        if (($data['type'] ?? '') === 'table') return RichBlockTable::class;
        if (($data['type'] ?? '') === 'details') return RichBlockDetails::class;
        if (($data['type'] ?? '') === 'map') return RichBlockMap::class;
        if (($data['type'] ?? '') === 'buttons') return RichBlockButtons::class;
        if (($data['type'] ?? '') === 'animation') return RichBlockAnimation::class;
        if (($data['type'] ?? '') === 'audio') return RichBlockAudio::class;
        if (($data['type'] ?? '') === 'document') return RichBlockDocument::class;
        if (($data['type'] ?? '') === 'photo') return RichBlockPhoto::class;
        if (($data['type'] ?? '') === 'video') return RichBlockVideo::class;
        if (($data['type'] ?? '') === 'voice_note') return RichBlockVoiceNote::class;
        if (($data['type'] ?? '') === 'thinking') return RichBlockThinking::class;
        return static::class;
    }
}
