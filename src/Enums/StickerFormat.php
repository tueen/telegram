<?php

declare(strict_types=1);

namespace Tueen\Telegram\Enums;

enum StickerFormat: string
{
    case STATIC = 'static';
    case ANIMATED = 'animated';
    case VIDEO = 'video';
}
