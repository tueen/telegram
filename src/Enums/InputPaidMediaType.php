<?php

declare(strict_types=1);

namespace Tueen\Telegram\Enums;

enum InputPaidMediaType: string
{
    case PHOTO = 'photo';
    case LIVE_PHOTO = 'live_photo';
    case VIDEO = 'video';
}
