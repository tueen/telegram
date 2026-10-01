<?php

declare(strict_types=1);

namespace Tueen\Telegram\Enums;

enum MaskPositionPoint: string
{
    case FOREHEAD = 'forehead';
    case EYES = 'eyes';
    case MOUTH = 'mouth';
    case CHIN = 'chin';
}
