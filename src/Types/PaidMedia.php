<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * This object describes paid media. Currently, it can be one of
 * - PaidMediaLivePhoto
 * - PaidMediaPhoto
 * - PaidMediaPreview
 * - PaidMediaVideo
 *
 * @link https://core.telegram.org/bots/api#paidmedia
 */
class PaidMedia extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {
        if (($data['type'] ?? '') === 'live_photo') return PaidMediaLivePhoto::class;
        if (($data['type'] ?? '') === 'photo') return PaidMediaPhoto::class;
        if (($data['type'] ?? '') === 'preview') return PaidMediaPreview::class;
        if (($data['type'] ?? '') === 'video') return PaidMediaVideo::class;
        return static::class;
    }
}
