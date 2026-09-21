<?php

namespace Tueen\Telegram\Enums;

enum ParseMode: string
{
    case MARKDOWN = 'MarkdownV2';
    case MARKDOWN_LEGACY = 'Markdown';
    case HTML = 'HTML';
}
