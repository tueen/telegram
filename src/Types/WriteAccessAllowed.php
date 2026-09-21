<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * This object represents a service message about a user allowing a bot to write messages after adding it to the attachment menu, launching a Web App from a link, or accepting an explicit request from a Web App sent by the method requestWriteAccess.
 *
 * @link https://core.telegram.org/bots/api#writeaccessallowed
 */
class WriteAccessAllowed extends Type
{
    /**
     * Optional. True, if the access was granted after the user accepted an explicit request from a Web App sent by the method requestWriteAccess
     */
    #[Field('from_request', required: false)]
    public private(set) ?bool $fromRequest = null;

    /**
     * Optional. Name of the Web App, if the access was granted when the Web App was launched from a link
     */
    #[Field('web_app_name', required: false)]
    public private(set) ?string $webAppName = null;

    /**
     * Optional. True, if the access was granted when the bot was added to the attachment or side menu
     */
    #[Field('from_attachment_menu', required: false)]
    public private(set) ?bool $fromAttachmentMenu = null;

}
