<?php

namespace Tueen\Telegram\Enums;

enum VideoQualityCodec: string
{
    case H264 = 'h264';
    case H265 = 'h265';
    case AV01 = 'av01';
}
