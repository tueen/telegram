<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a service message about a user allowing a bot to write messages after adding it to the attachment menu, launching a Web App from a link, or accepting an explicit request from a Web App sent by the method requestWriteAccess, or about a user being invited to the private chat with the bot by the owner of the bot.
 *
 * @link https://core.telegram.org/bots/api#writeaccessallowed
 */
class WriteAccessAllowed extends Type
{
    /**
     * Optional. True, if the access was granted after the user accepted an explicit request from a Web App sent by the method requestWriteAccess
     */
    #[Field('from_request', required: false)]
    private(set) ?bool $fromRequest = null;

    /**
     * Optional. Name of the Web App, if the access was granted when the Web App was launched from a link
     */
    #[Field('web_app_name', required: false)]
    private(set) ?string $webAppName = null;

    /**
     * Optional. True, if the access was granted when the bot was added to the attachment or side menu
     */
    #[Field('from_attachment_menu', required: false)]
    private(set) ?bool $fromAttachmentMenu = null;

    /**
     * Optional. The owner of the bot, if the access was granted when the owner invited the user to the private chat with the bot. Bot owners can invite up to 10 users at once by pressing the link https://t.me/botinvite/<bot_username>.
     */
    #[Field('inviter', required: false)]
    private(set) ?User $inviter = null;

}
