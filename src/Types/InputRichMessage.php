<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Describes a rich message to be sent. Exactly one of the fields html, markdown, or blocks must be used.
 *
 * @link https://core.telegram.org/bots/api#inputrichmessage
 */
class InputRichMessage extends Type
{
    /**
     * Optional. Content of the rich message to send described as a list of blocks
     * @var InputRichBlock[]|null
     */
    #[Field('blocks', required: false)]
    #[ArrayOf(InputRichBlock::class)]
    public private(set) ?array $blocks = null;

    /**
     * Optional. Content of the rich message to send described using HTML formatting. See rich message formatting options for more details. Use media field to specify the media used in the message.
     */
    #[Field('html', required: false)]
    public private(set) ?string $html = null;

    /**
     * Optional. Content of the rich message to send described using Markdown formatting. See rich message formatting options for more details. Use media field to specify the media used in the message.
     */
    #[Field('markdown', required: false)]
    public private(set) ?string $markdown = null;

    /**
     * Optional. List of media that are specified in the markdown or html fields using tg://photo?id=, tg://video?id=, tg://document?id=, and tg://audio?id= links
     * @var InputRichMessageMedia[]|null
     */
    #[Field('media', required: false)]
    #[ArrayOf(InputRichMessageMedia::class)]
    public private(set) ?array $media = null;

    /**
     * Optional. Pass True if the rich message must be shown right-to-left
     */
    #[Field('is_rtl', required: false)]
    public private(set) ?bool $isRtl = null;

    /**
     * Optional. Pass True to skip automatic detection of entities (e.g., URLs, email addresses, username mentions, hashtags, cashtags, bot commands, or phone numbers) in the text
     */
    #[Field('skip_entity_detection', required: false)]
    public private(set) ?bool $skipEntityDetection = null;

}
