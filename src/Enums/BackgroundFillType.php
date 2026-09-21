<?php

namespace Tueen\Telegram\Enums;

enum BackgroundFillType: string
{
    case SOLID = 'solid';
    case GRADIENT = 'gradient';
    case FREEFORM_GRADIENT = 'freeform_gradient';
}
