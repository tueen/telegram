<?php

namespace Tueen\Telegram\Enums;

enum PaidMediaType: string
{
    case PREVIEW = 'preview';
    case PHOTO = 'photo';
    case LIVE_PHOTO = 'live_photo';
    case VIDEO = 'video';
}
