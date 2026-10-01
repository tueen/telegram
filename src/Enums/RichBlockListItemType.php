<?php

declare(strict_types=1);

namespace Tueen\Telegram\Enums;

enum RichBlockListItemType: string
{
    case LOWERCASE_LETTERS = 'a';
    case UPPERCASE_LETTERS = 'A';
    case LOWERCASE_ROMAN_NUMERALS = 'i';
    case UPPERCASE_ROMAN_NUMERALS = 'I';
    case DECIMAL_NUMBERS = '1';
}
