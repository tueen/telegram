<?php

namespace Tueen\Telegram\Enums;

enum StickerFormat: string
{
    case STATIC = 'static';
    case ANIMATED = 'animated';
    case VIDEO = 'video';
}
