<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Describes a service message about checklist tasks marked as done or not done.
 *
 * @link https://core.telegram.org/bots/api#checklisttasksdone
 */
class ChecklistTasksDone extends Type
{
    /**
     * Optional. Message containing the checklist whose tasks were marked as done or not done. Note that the Message object in this field will not contain the reply_to_message field even if it itself is a reply.
     */
    #[Field('checklist_message', required: false)]
    public private(set) ?Message $checklistMessage = null;

    /**
     * Optional. Identifiers of the tasks that were marked as done
     * @var Integer[]|null
     */
    #[Field('marked_as_done_task_ids', required: false)]
    public private(set) ?array $markedAsDoneTaskIds = null;

    /**
     * Optional. Identifiers of the tasks that were marked as not done
     * @var Integer[]|null
     */
    #[Field('marked_as_not_done_task_ids', required: false)]
    public private(set) ?array $markedAsNotDoneTaskIds = null;

}
