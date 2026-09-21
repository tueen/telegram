<?php

declare(strict_types=1);

namespace Tueen\Telegram\Properties;

enum ParseMode: string
{
    case HTML = 'HTML';
    case Markdown = 'Markdown';
    case MarkdownV2 = 'MarkdownV2';
}
