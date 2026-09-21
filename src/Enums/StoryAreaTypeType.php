<?php

namespace Tueen\Telegram\Enums;

enum StoryAreaTypeType: string
{
    case LOCATION = 'location';
    case SUGGESTED_REACTION = 'suggested_reaction';
    case LINK = 'link';
    case WEATHER = 'weather';
    case UNIQUE_GIFT = 'unique_gift';
}
