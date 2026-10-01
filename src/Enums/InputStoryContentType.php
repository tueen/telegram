<?php

declare(strict_types=1);

namespace Tueen\Telegram\Enums;

enum InputStoryContentType: string
{
    case PHOTO = 'photo';
    case VIDEO = 'video';
}
