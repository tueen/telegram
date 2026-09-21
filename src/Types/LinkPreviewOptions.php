<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * Describes the options used for link preview generation.
 *
 * @link https://core.telegram.org/bots/api#linkpreviewoptions
 */
class LinkPreviewOptions extends Type
{
    /**
     * Optional. True, if the link preview is disabled
     */
    #[Field('is_disabled', required: false)]
    public private(set) ?bool $isDisabled = null;

    /**
     * Optional. URL to use for the link preview. If empty, then the first URL found in the message text will be used.
     */
    #[Field('url', required: false)]
    public private(set) ?string $url = null;

    /**
     * Optional. True, if the media in the link preview is supposed to be shrunk; ignored if the URL isn't explicitly specified or media size change isn't supported for the preview
     */
    #[Field('prefer_small_media', required: false)]
    public private(set) ?bool $preferSmallMedia = null;

    /**
     * Optional. True, if the media in the link preview is supposed to be enlarged; ignored if the URL isn't explicitly specified or media size change isn't supported for the preview
     */
    #[Field('prefer_large_media', required: false)]
    public private(set) ?bool $preferLargeMedia = null;

    /**
     * Optional. True, if the link preview must be shown above the message text; otherwise, the link preview will be shown below the message text
     */
    #[Field('show_above_text', required: false)]
    public private(set) ?bool $showAboveText = null;

}
