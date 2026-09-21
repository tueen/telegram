<?php

declare(strict_types=1);

namespace Tueen\Telegram\Enums;

enum ParseMode: string
{
    case HTML = 'HTML';
    case Markdown = 'Markdown';
    case MarkdownV2 = 'MarkdownV2';
}
