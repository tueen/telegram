<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * Describes a Web App.
 *
 * @link https://core.telegram.org/bots/api#webappinfo
 */
class WebAppInfo extends Type
{
    /**
     * An HTTPS URL of a Web App to be opened with additional data as specified in Initializing Web Apps
     */
    #[Field('url', required: true)]
    private(set) ?string $url = null;

}
