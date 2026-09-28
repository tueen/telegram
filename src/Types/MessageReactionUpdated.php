<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a change of a reaction on a message performed by a user.
 *
 * @link https://core.telegram.org/bots/api#messagereactionupdated
 */
class MessageReactionUpdated extends Type
{
    /**
     * The chat containing the message the user reacted to
     */
    #[Field('chat', required: true)]
    private(set) ?Chat $chat = null;

    /**
     * Unique identifier of the message inside the chat
     */
    #[Field('message_id', required: true)]
    private(set) ?int $messageId = null;

    /**
     * Optional. The user that changed the reaction, if the user isn't anonymous
     */
    #[Field('user', required: false)]
    private(set) ?User $user = null;

    /**
     * Optional. The chat on behalf of which the reaction was changed, if the user is anonymous
     */
    #[Field('actor_chat', required: false)]
    private(set) ?Chat $actorChat = null;

    /**
     * Date of the change in Unix time
     */
    #[Field('date', required: true)]
    private(set) ?int $date = null;

    /**
     * Previous list of reaction types that were set by the user
     * @var ReactionType[]|null
     */
    #[Field('old_reaction', required: true)]
    #[ArrayOf(ReactionType::class)]
    private(set) ?array $oldReaction = null;

    /**
     * New list of reaction types that have been set by the user
     * @var ReactionType[]|null
     */
    #[Field('new_reaction', required: true)]
    #[ArrayOf(ReactionType::class)]
    private(set) ?array $newReaction = null;

}
