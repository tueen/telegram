<?php

declare(strict_types=1);

namespace Tueen\Telegram\Enums;

enum PollType: string
{
    case REGULAR = 'regular';
    case QUIZ = 'quiz';
}
