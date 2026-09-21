<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object describes the content of a story to post. Currently, it can be one of
 * - InputStoryContentPhoto
 * - InputStoryContentVideo
 *
 * @link https://core.telegram.org/bots/api#inputstorycontent
 */
class InputStoryContent extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {

        return static::class;
    }
}
