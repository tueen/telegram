<?php

declare(strict_types=1);

namespace Tueen\Telegram\Enums;

enum ButtonStyle: string
{
    case DANGER = 'danger';
    case SUCCESS = 'success';
    case PRIMARY = 'primary';
    case LINK = 'link';
}
