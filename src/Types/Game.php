<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a game. Use BotFather to create and edit games, their short names will act as unique identifiers.
 *
 * @link https://core.telegram.org/bots/api#game
 */
class Game extends Type
{
    /**
     * Title of the game
     */
    #[Field('title', required: true)]
    public private(set) string $title;

    /**
     * Description of the game
     */
    #[Field('description', required: true)]
    public private(set) string $description;

    /**
     * Photo that will be displayed in the game message in chats
     * @var PhotoSize[]|null
     */
    #[Field('photo', required: true)]
    #[ArrayOf(PhotoSize::class)]
    public private(set) array $photo;

    /**
     * Optional. Brief description of the game or high scores included in the game message. Can be automatically edited to include current high scores for the game when the bot calls setGameScore, or manually edited using editMessageText. 0-4096 characters.
     */
    #[Field('text', required: false)]
    public private(set) ?string $text = null;

    /**
     * Optional. Special entities that appear in text, such as usernames, URLs, bot commands, etc.
     * @var MessageEntity[]|null
     */
    #[Field('text_entities', required: false)]
    #[ArrayOf(MessageEntity::class)]
    public private(set) ?array $textEntities = null;

    /**
     * Optional. Animation that will be displayed in the game message in chats. Upload via BotFather.
     */
    #[Field('animation', required: false)]
    public private(set) ?Animation $animation = null;

}
