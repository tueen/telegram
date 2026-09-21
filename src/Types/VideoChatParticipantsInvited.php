<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\User;

/**
 * This object represents a service message about new members invited to a video chat.
 *
 * @link https://core.telegram.org/bots/api#videochatparticipantsinvited
 */
class VideoChatParticipantsInvited extends Type
{
    /**
     * New members that were invited to the video chat
     * @var User[]|null
     */
    #[Field('users', required: true)]
    #[ArrayOf(User::class)]
    public private(set) array $users;

}
