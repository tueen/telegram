<?php

declare(strict_types=1);

namespace Tueen\Telegram\Enums;

enum ParseMode: string
{
    case HTML = 'HTML';
    case MARKDOWN_V2 = 'MarkdownV2';
    case MARKDOWN = 'Markdown';
}
