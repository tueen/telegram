<?php

declare(strict_types=1);

namespace Tueen\Telegram\Enums;

enum ChatBoostSourceSource: string
{
    case PREMIUM = 'premium';
    case GIFT_CODE = 'gift_code';
    case GIVEAWAY = 'giveaway';
}
